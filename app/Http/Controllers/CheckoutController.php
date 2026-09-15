<?php

namespace App\Http\Controllers;

use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Services\ActivityLogger;
use App\Services\CartService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    protected $transactionService;
    protected $cartService;
    protected $activityLogger;

    public function __construct(
        TransactionService $transactionService,
        CartService $cartService,
        ActivityLogger $activityLogger
    ) {
        $this->transactionService = $transactionService;
        $this->cartService = $cartService;
        $this->activityLogger = $activityLogger;
    }

    /**
     * Show checkout form
     */
    public function index()
    {
        // Get cart items from service
        $items = $this->cartService->getItems();

        if (empty($items)) {
            return redirect()->route('catalog')->with('error', 'Your cart is empty');
        }

        // Validate stock levels and adjust cart if needed
        $stockAdjustments = [];
        foreach ($items as $item) {
            if ($item['variant_id']) {
                $variant = \App\Models\ProductVariant::find($item['variant_id']);
                if ($variant) {
                    // Check if cart quantity exceeds available stock
                    if ($item['quantity'] > $variant->stock) {
                        // Adjust cart to match available stock
                        if ($variant->stock > 0) {
                            $this->cartService->updateItem(
                                $item['id'],
                                $variant->stock,
                                $item['variant_id']
                            );
                            $stockAdjustments[] = [
                                'product' => $item['name'],
                                'size' => $item['variant_size'],
                                'old_quantity' => $item['quantity'],
                                'new_quantity' => $variant->stock,
                            ];
                        } else {
                            // Remove item if out of stock
                            $this->cartService->removeItem($item['id'], $item['variant_id']);
                            $stockAdjustments[] = [
                                'product' => $item['name'],
                                'size' => $item['variant_size'],
                                'old_quantity' => $item['quantity'],
                                'new_quantity' => 0,
                                'removed' => true,
                            ];
                        }
                    }
                }
            }
        }

        // Reload cart items after adjustments
        $items = $this->cartService->getItems();

        // Check if cart is empty after adjustments
        if (empty($items)) {
            $message = 'All items in your cart are out of stock.';
            if (!empty($stockAdjustments)) {
                $message .= ' Please check the catalog for available products.';
            }
            return redirect()->route('catalog')->with('error', $message);
        }

        // Calculate totals
        $subtotal = 0;
        $cartItems = [];

        foreach ($items as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                    'variant_size' => $item['variant_size'] ?? null,
                ];
                $subtotal += $item['subtotal'];
            }
        }

        // Coupon Logic
        $appliedCoupon = $this->cartService->getAppliedCoupon();
        $discountAmount = 0;
        if ($appliedCoupon) {
            $discountAmount = $this->cartService->getDiscountAmount($subtotal);
        }

        // Get shipping cost from settings
        $shippingCost = Setting::get('shipping_cost', 10000);
        $freeShippingMin = Setting::get('free_shipping_min', 100000);

        // Apply free shipping if applicable (check against subtotal after discount? Usually before, but let's stick to subtotal)
        // Usually free shipping is based on subtotal of items.
        if ($subtotal >= $freeShippingMin) {
            $shippingCost = 0;
        }

        $total = $subtotal - $discountAmount + $shippingCost;

        // Pass stock adjustments to view
        return view('checkout', compact('cartItems', 'subtotal', 'shippingCost', 'total', 'appliedCoupon', 'discountAmount', 'stockAdjustments'));
    }

    /**
     * Process checkout and redirect to WhatsApp
     */
    public function process(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_whatsapp' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_province' => 'required|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:10',
            'shipping_notes' => 'nullable|string',
        ]);

        // Get cart items from service
        $items = $this->cartService->getItems();

        if (empty($items)) {
            return redirect()->route('catalog')->with('error', 'Your cart is empty');
        }

        try {
            DB::beginTransaction();

            // Prepare order data
            $orderData = [
                'customer_name' => $validated['customer_name'],
                'customer_whatsapp' => $validated['customer_whatsapp'],
                'customer_email' => $validated['customer_email'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_province' => $validated['shipping_province'],
                'shipping_postal_code' => $validated['shipping_postal_code'] ?? null,
                'shipping_notes' => $validated['shipping_notes'] ?? null,
                'status' => Order::STATUS_PENDING,
            ];

            // Set user_id for authenticated users or guest_customer_id for guests
            $guestCustomerId = null;
            if (auth()->check()) {
                $orderData['user_id'] = auth()->id();
            } else {
                // Generate or retrieve guest customer ID from cookie
                $guestCustomerId = $request->cookie('guest_customer_id');
                if (!$guestCustomerId) {
                    $guestCustomerId = \Str::uuid()->toString();
                    // Queue cookie for 1 year (525600 minutes)
                    cookie()->queue('guest_customer_id', $guestCustomerId, 525600);
                }
                $orderData['guest_customer_id'] = $guestCustomerId;
            }


            // Prepare order items
            $cartItems = $this->cartService->getItems();
            $orderItems = [];
            $subtotal = 0;

            foreach ($cartItems as $item) {
                $product = Product::find($item['id']);
                if (!$product) {
                    continue;
                }

                $subtotal += $item['subtotal'];

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name' => $product->name,
                    'variant_name' => $item['variant_size'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ];
            }

            // Coupon Calculation
            $appliedCoupon = $this->cartService->getAppliedCoupon();
            $discountAmount = 0;
            if ($appliedCoupon) {
                $discountAmount = $this->cartService->getDiscountAmount($subtotal);
            }

            // Calculate shipping
            $shippingCost = Setting::get('shipping_cost', 10000);
            $freeShippingMin = Setting::get('free_shipping_min', 100000);

            if ($subtotal >= $freeShippingMin) {
                $shippingCost = 0;
            }

            $orderData['subtotal'] = $subtotal;
            $orderData['shipping_cost'] = $shippingCost;
            $orderData['discount'] = $discountAmount;
            $orderData['total'] = $subtotal - $discountAmount + $shippingCost;

            // Create order
            $order = $this->transactionService->createOrder($orderData, $orderItems);

            // Record Coupon Usage
            if ($appliedCoupon && $discountAmount > 0) {
                CouponUsage::create([
                    'coupon_id' => $appliedCoupon->id,
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'guest_customer_id' => $guestCustomerId,
                    'discount_amount' => $discountAmount,
                ]);
            }

            // Update order status to contacted (sent to WhatsApp)
            $order->update(['status' => Order::STATUS_CONTACTED]);

            DB::commit();

            // Log order placement activity
            if (auth()->check()) {
                $this->activityLogger->logOrderPlaced($order->order_number, $order->total);
            }

            // Clear cart using service
            $this->cartService->clear();

            // Generate WhatsApp message
            $whatsappUrl = $this->generateWhatsAppUrl($order);

            // Redirect to success page with WhatsApp URL
            return redirect()->route('checkout.success', $order->uuid)->with('whatsapp_url', $whatsappUrl);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show success page
     */
    public function success($orderUuid)
    {
        $order = Order::with(['couponUsage.coupon'])->where('uuid', $orderUuid)->firstOrFail();
        $whatsappUrl = $this->generateWhatsAppUrl($order);

        return view('checkout-success', compact('order', 'whatsappUrl'));
    }

    /**
     * Generate WhatsApp URL with order details
     */
    protected function generateWhatsAppUrl(Order $order): string
    {
        $usesHeaderNumber = Setting::get('whatsapp_template_use_header_number', true);
        $adminNumber = $usesHeaderNumber
            ? Setting::get('whatsapp_admin_number', '6281234567890')
            : Setting::get('whatsapp_template_number', Setting::get('whatsapp_admin_number', '6281234567890'));
        $adminName = Setting::get('whatsapp_admin_name', 'Admin');

        $orderItems = '';

        foreach ($order->items as $index => $item) {
            $num = $index + 1;
            $orderItems .= "{$num}. {$item->product_name}\n";
            if ($item->variant_name) {
                $orderItems .= "   Ukuran: {$item->variant_name}\n";
            }
            $orderItems .= '   Harga: Rp '.number_format($item->price, 0, ',', '.')."\n";
            $orderItems .= "   Jumlah: {$item->quantity}\n";
            $orderItems .= '   Subtotal: Rp '.number_format($item->subtotal, 0, ',', '.')."\n\n";
        }

        $template = Setting::get('whatsapp_message_template', $this->defaultWhatsAppMessageTemplate());
        $template = filled($template) ? $template : $this->defaultWhatsAppMessageTemplate();
        $message = strtr($template, [
            '{admin_name}' => $adminName,
            '{order_number}' => $order->order_number,
            '{order_items}' => rtrim($orderItems),
            '{subtotal}' => 'Rp '.number_format($order->subtotal, 0, ',', '.'),
            '{discount}' => 'Rp '.number_format($order->discount, 0, ',', '.'),
            '{discount_line}' => $order->discount > 0
                ? 'Diskon: -Rp '.number_format($order->discount, 0, ',', '.')."\n"
                : '',
            '{shipping_cost}' => 'Rp '.number_format($order->shipping_cost, 0, ',', '.'),
            '{total}' => 'Rp '.number_format($order->total, 0, ',', '.'),
            '{customer_name}' => $order->customer_name,
            '{customer_whatsapp}' => $order->customer_whatsapp,
            '{customer_email}' => $order->customer_email ?? '',
            '{customer_email_line}' => $order->customer_email ? "Email: {$order->customer_email}\n" : '',
            '{shipping_address}' => $order->shipping_address,
            '{shipping_city}' => $order->shipping_city,
            '{shipping_province}' => $order->shipping_province,
            '{shipping_postal_code}' => $order->shipping_postal_code ?? '',
            '{shipping_postal_code_line}' => $order->shipping_postal_code
                ? "Kode Pos: {$order->shipping_postal_code}\n"
                : '',
            '{shipping_notes}' => $order->shipping_notes ?? '',
            '{shipping_notes_line}' => $order->shipping_notes ? "Catatan: {$order->shipping_notes}\n" : '',
        ]);

        // Encode message for URL
        $encodedMessage = urlencode($message);

        // Generate wa.me URL
        return "https://wa.me/{$adminNumber}?text={$encodedMessage}";
    }

    private function defaultWhatsAppMessageTemplate(): string
    {
        return <<<'TEMPLATE'
Halo *{admin_name}*,

Saya ingin melakukan pemesanan dengan detail sebagai berikut:

*Nomor Pesanan: {order_number}*

*Daftar Produk:*
{order_items}

*Ringkasan Pembayaran:*
Subtotal: {subtotal}
{discount_line}Ongkir: {shipping_cost}
━━━━━━━━━━━━━━━
*TOTAL: {total}*

*Informasi Pengiriman:*
Nama: {customer_name}
WhatsApp: {customer_whatsapp}
{customer_email_line}Alamat: {shipping_address}
Kota: {shipping_city}
Provinsi: {shipping_province}
{shipping_postal_code_line}{shipping_notes_line}

Mohon dikonfirmasi. Terima kasih!
TEMPLATE;
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if variant belongs to product
        $product = Product::find($validated['product_id']);
        $variant = \App\Models\ProductVariant::find($validated['product_variant_id']);

        if (!$variant || $variant->product_id !== $product->id) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid variant for this product',
            ], 400);
        }

        // Get current quantity in cart for this variant
        $currentCartQuantity = 0;
        if (Auth::check()) {
            $cartItem = \App\Models\CartItem::where('user_id', Auth::id())
                ->where('product_id', $validated['product_id'])
                ->where('product_variant_id', $validated['product_variant_id'])
                ->first();
            $currentCartQuantity = $cartItem ? $cartItem->quantity : 0;
        } else {
            $cart = Session::get('cart', []);
            $key = $validated['product_id'] . '_' . $validated['product_variant_id'];
            $currentCartQuantity = isset($cart[$key]) ? $cart[$key]['quantity'] : 0;
        }

        // Check if total quantity (current + new) exceeds stock
        $totalQuantity = $currentCartQuantity + $validated['quantity'];
        if (!$variant->hasStock($totalQuantity)) {
            $remainingStock = max(0, $variant->stock - $currentCartQuantity);
            return response()->json([
                'success' => false,
                'message' => $remainingStock > 0
                    ? "You can only add {$remainingStock} more of this item. Current cart has {$currentCartQuantity}, available stock: {$variant->stock}"
                    : "This item is already at maximum quantity in your cart. Available stock: {$variant->stock}",
            ], 400);
        }

        $this->cartService->addItem(
            $validated['product_id'],
            $validated['quantity'],
            $validated['product_variant_id']
        );

        // Log activity if user is authenticated
        if (auth()->check()) {
            if ($product) {
                $this->activityLogger->logCartAdd($product->id, $product->name . ' - ' . $variant->size, $validated['quantity']);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart_count' => $this->cartService->getCount(),
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
            'product_variant_id' => 'nullable|exists:product_variants,id',
        ]);

        // Check stock availability for the variant
        if ($validated['product_variant_id']) {
            $variant = \App\Models\ProductVariant::find($validated['product_variant_id']);

            if ($variant && $validated['quantity'] > 0) {
                if (!$variant->hasStock($validated['quantity'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock. Available: ' . $variant->stock,
                    ], 400);
                }
            }
        }

        $this->cartService->updateItem(
            $validated['product_id'],
            $validated['quantity'],
            $validated['product_variant_id'] ?? null
        );

        // Recalculate everything for response if needed, 
        // but for now simple response is fine, frontend might reload or request cart data
        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'cart_count' => $this->cartService->getCount(),
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
        ]);

        // Log activity if user is authenticated before removing
        if (auth()->check()) {
            $product = Product::find($validated['product_id']);
            if ($product) {
                $this->activityLogger->logCartRemove($product->id, $product->name);
            }
        }

        $this->cartService->removeItem(
            $validated['product_id'],
            $validated['product_variant_id'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart successfully',
            'cart_count' => $this->cartService->getCount(),
        ]);
    }

    /**
     * Get cart data
     */
    public function getCart()
    {
        $cartItems = $this->cartService->getItems();
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item['subtotal'];
        }

        // Calculate Discount
        $discountAmount = $this->cartService->getDiscountAmount($subtotal);

        // Get shipping cost
        $shippingCost = Setting::get('shipping_cost', 10000);
        $freeShippingMin = Setting::get('free_shipping_min', 100000);

        if ($subtotal >= $freeShippingMin) {
            $shippingCost = 0;
        }

        $total = $subtotal - $discountAmount + $shippingCost;

        return response()->json([
            'items' => $cartItems,
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'count' => $this->cartService->getCount(),
        ]);
    }

    /**
     * Apply Coupon
     */
    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
        ]);

        $result = $this->cartService->applyCoupon($validated['code']);

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        return response()->json($result);
    }

    /**
     * Remove Coupon
     */
    public function removeCoupon()
    {
        $this->cartService->removeCoupon();

        return response()->json([
            'success' => true,
            'message' => 'Kupon berhasil dihapus.'
        ]);
    }
}
