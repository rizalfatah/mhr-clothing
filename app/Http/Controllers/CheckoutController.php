<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Show checkout form
     */
    public function index()
    {
        // Get cart from session
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('catalog')->with('error', 'Keranjang belanja Anda kosong');
        }

        // Calculate totals
        $subtotal = 0;
        $cartItems = [];

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                ];
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

        // Get cart from session
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('catalog')->with('error', 'Keranjang belanja Anda kosong');
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

            // Prepare order items
            $items = [];
            $subtotal = 0;

            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                if (!$product) {
                    continue;
                }

                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
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
            $order = $this->transactionService->createOrder($orderData, $items);

            // Update order status to contacted (sent to WhatsApp)
            $order->update(['status' => Order::STATUS_CONTACTED]);

            DB::commit();

            // Clear cart
            Session::forget('cart');

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
        $orderDetails = "📋 *Nomor Pesanan: {$order->order_number}*\n\n";
        $orderDetails .= "*Daftar Produk:*\n";

        foreach ($order->items as $index => $item) {
            $num = $index + 1;
            $orderDetails .= "{$num}. {$item->product_name}\n";
            $orderDetails .= "   Harga: Rp " . number_format($item->price, 0, ',', '.') . "\n";
            $orderDetails .= "   Jumlah: {$item->quantity}\n";
            $orderDetails .= "   Subtotal: Rp " . number_format($item->subtotal, 0, ',', '.') . "\n\n";
        }

        $orderDetails .= "💰 *Ringkasan Pembayaran:*\n";
        $orderDetails .= "Subtotal: Rp " . number_format($order->subtotal, 0, ',', '.') . "\n";
        $orderDetails .= "Ongkir: Rp " . number_format($order->shipping_cost, 0, ',', '.') . "\n";
        $orderDetails .= "━━━━━━━━━━━━━━━\n";
        $orderDetails .= "*TOTAL: Rp " . number_format($order->total, 0, ',', '.') . "*\n\n";

        // Build shipping info
        $shippingInfo = "📦 *Informasi Pengiriman:*\n";
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
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Session::get('cart', []);

        $productId = $validated['product_id'];
        $quantity = $validated['quantity'];

        // If product already in cart, update quantity
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'quantity' => $quantity,
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cart_count' => count($cart),
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

        $cart = Session::get('cart', []);
        $productId = $validated['product_id'];
        $quantity = $validated['quantity'];

        if ($quantity == 0) {
            // Remove item from cart
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $quantity;
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diperbarui',
            'cart_count' => count($cart),
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

        $cart = Session::get('cart', []);
        $productId = $validated['product_id'];

        unset($cart[$productId]);

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari keranjang',
            'cart_count' => count($cart),
        ]);
    }

    /**
     * Get cart data
     */
    public function getCart()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::with('images')->find($productId);
            if ($product) {
                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                    'image' => $product->images->first()?->image_path,
                ];
            }
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
