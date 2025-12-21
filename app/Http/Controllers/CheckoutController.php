<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Services\ActivityLogger;
use App\Services\CartService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
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
                ];
                $subtotal += $item['subtotal'];
            }
        }

        // Get shipping cost from settings
        $shippingCost = Setting::get('shipping_cost', 10000);
        $freeShippingMin = Setting::get('free_shipping_min', 100000);

        // Apply free shipping if applicable
        if ($subtotal >= $freeShippingMin) {
            $shippingCost = 0;
        }

        $total = $subtotal + $shippingCost;

        return view('checkout', compact('cartItems', 'subtotal', 'shippingCost', 'total'));
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

            // Calculate shipping
            $shippingCost = Setting::get('shipping_cost', 10000);
            $freeShippingMin = Setting::get('free_shipping_min', 100000);

            if ($subtotal >= $freeShippingMin) {
                $shippingCost = 0;
            }

            $orderData['subtotal'] = $subtotal;
            $orderData['shipping_cost'] = $shippingCost;
            $orderData['total'] = $subtotal + $shippingCost;

            // Create order
            $order = $this->transactionService->createOrder($orderData, $orderItems);

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
            return redirect()->route('checkout.success', $order->id)->with('whatsapp_url', $whatsappUrl);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show success page
     */
    public function success($orderId)
    {
        $order = Order::findOrFail($orderId);
        $whatsappUrl = $this->generateWhatsAppUrl($order);

        return view('checkout-success', compact('order', 'whatsappUrl'));
    }

    /**
     * Generate WhatsApp URL with order details
     */
    protected function generateWhatsAppUrl(Order $order): string
    {
        $adminNumber = Setting::get('whatsapp_admin_number', '6281234567890');
        $adminName = Setting::get('whatsapp_admin_name', 'Admin');

        // Build order details
        $orderDetails = "*Nomor Pesanan: {$order->order_number}*\n\n";
        $orderDetails .= "*Daftar Produk:*\n";

        foreach ($order->items as $index => $item) {
            $num = $index + 1;
            $orderDetails .= "{$num}. {$item->product_name}\n";
            if ($item->variant_name) {
                $orderDetails .= "   Ukuran: {$item->variant_name}\n";
            }
            $orderDetails .= "   Harga: Rp " . number_format($item->price, 0, ',', '.') . "\n";
            $orderDetails .= "   Jumlah: {$item->quantity}\n";
            $orderDetails .= "   Subtotal: Rp " . number_format($item->subtotal, 0, ',', '.') . "\n\n";
        }

        $orderDetails .= "*Ringkasan Pembayaran:*\n";
        $orderDetails .= "Subtotal: Rp " . number_format($order->subtotal, 0, ',', '.') . "\n";
        $orderDetails .= "Ongkir: Rp " . number_format($order->shipping_cost, 0, ',', '.') . "\n";
        $orderDetails .= "━━━━━━━━━━━━━━━\n";
        $orderDetails .= "*TOTAL: Rp " . number_format($order->total, 0, ',', '.') . "*\n\n";

        // Build shipping info
        $shippingInfo = "*Informasi Pengiriman:*\n";
        $shippingInfo .= "Nama: {$order->customer_name}\n";
        $shippingInfo .= "WhatsApp: {$order->customer_whatsapp}\n";
        if ($order->customer_email) {
            $shippingInfo .= "Email: {$order->customer_email}\n";
        }
        $shippingInfo .= "Alamat: {$order->shipping_address}\n";
        $shippingInfo .= "Kota: {$order->shipping_city}\n";
        $shippingInfo .= "Provinsi: {$order->shipping_province}\n";
        if ($order->shipping_postal_code) {
            $shippingInfo .= "Kode Pos: {$order->shipping_postal_code}\n";
        }
        if ($order->shipping_notes) {
            $shippingInfo .= "Catatan: {$order->shipping_notes}\n";
        }

        // Complete message
        $message = "Halo *{$adminName}*,\n\n";
        $message .= "Saya ingin melakukan pemesanan dengan detail sebagai berikut:\n\n";
        $message .= $orderDetails;
        $message .= $shippingInfo;
        $message .= "\n\nMohon dikonfirmasi. Terima kasih!";

        // Encode message for URL
        $encodedMessage = urlencode($message);

        // Generate wa.me URL
        return "https://wa.me/{$adminNumber}?text={$encodedMessage}";
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

        // Check stock availability
        if (!$variant->hasStock($validated['quantity'])) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock for selected size. Available: ' . $variant->stock,
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
        ]);

        $this->cartService->updateItem($validated['product_id'], $validated['quantity']);

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
        ]);

        // Log activity if user is authenticated before removing
        if (auth()->check()) {
            $product = Product::find($validated['product_id']);
            if ($product) {
                $this->activityLogger->logCartRemove($product->id, $product->name);
            }
        }

        $this->cartService->removeItem($validated['product_id']);

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

        // Get shipping cost
        $shippingCost = Setting::get('shipping_cost', 10000);
        $freeShippingMin = Setting::get('free_shipping_min', 100000);

        if ($subtotal >= $freeShippingMin) {
            $shippingCost = 0;
        }

        $total = $subtotal + $shippingCost;

        return response()->json([
            'items' => $cartItems,
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'count' => count($cartItems),
        ]);
    }
}
