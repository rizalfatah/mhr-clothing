<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Add item to cart (database or session based on auth status)
     */
    public function addItem(int $productId, int $quantity, ?int $variantId = null): void
    {
        if (Auth::check()) {
            $this->addItemToDatabase($productId, $quantity, $variantId);
        } else {
            $this->addItemToSession($productId, $quantity, $variantId);
        }
    }

    /**
     * Update item quantity in cart
     */
    public function updateItem(int $productId, int $quantity, ?int $variantId = null): void
    {
        if (Auth::check()) {
            $this->updateItemInDatabase($productId, $quantity, $variantId);
        } else {
            $this->updateItemInSession($productId, $quantity, $variantId);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeItem(int $productId, ?int $variantId = null): void
    {
        if (Auth::check()) {
            $this->removeItemFromDatabase($productId, $variantId);
        } else {
            $this->removeItemFromSession($productId, $variantId);
        }
    }

    /**
     * Get all cart items with product details
     */
    public function getItems(): array
    {
        if (Auth::check()) {
            return $this->getItemsFromDatabase();
        } else {
            return $this->getItemsFromSession();
        }
    }

    /**
     * Get total number of unique items in cart
     */
    public function getCount(): int
    {
        if (Auth::check()) {
            return Auth::user()->cartItems()->count();
        } else {
            $cart = Session::get('cart', []);
            return count($cart);
        }
    }

    /**
     * Clear the entire cart
     */
    public function clear(): void
    {
        if (Auth::check()) {
            Auth::user()->cartItems()->delete();
        } else {
            Session::forget('cart');
        }
        $this->removeCoupon();
    }

    /**
     * Merge session cart into database when user logs in
     */
    public function mergeSessionToDatabase(): void
    {
        $sessionCart = Session::get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $productId => $item) {
            $existingCartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if ($existingCartItem) {
                // Add quantities together
                $existingCartItem->quantity += $item['quantity'];
                $existingCartItem->save();
            } else {
                // Create new cart item
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        // Clear session cart after merging
        Session::forget('cart');
    }

    /**
     * Add item to database (for authenticated users)
     */
    protected function addItemToDatabase(int $productId, int $quantity, ?int $variantId = null): void
    {
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
            ]);
        }
    }

    /**
     * Add item to session (for guest users)
     */
    protected function addItemToSession(int $productId, int $quantity, ?int $variantId = null): void
    {
        $cart = Session::get('cart', []);
        $key = $productId . '_' . $variantId;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
            ];
        }

        Session::put('cart', $cart);
    }

    /**
     * Update item in database (for authenticated users)
     */
    protected function updateItemInDatabase(int $productId, int $quantity, ?int $variantId = null): void
    {
        if ($quantity <= 0) {
            $this->removeItemFromDatabase($productId, $variantId);
            return;
        }

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
        }
    }

    /**
     * Update item in session (for guest users)
     */
    protected function updateItemInSession(int $productId, int $quantity, ?int $variantId = null): void
    {
        $cart = Session::get('cart', []);
        $key = $productId . '_' . $variantId;

        if ($quantity <= 0) {
            unset($cart[$key]);
        } else {
            if (isset($cart[$key])) {
                $cart[$key]['quantity'] = $quantity;
            }
        }

        Session::put('cart', $cart);
    }

    /**
     * Remove item from database (for authenticated users)
     */
    protected function removeItemFromDatabase(int $productId, ?int $variantId = null): void
    {
        CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->delete();
    }

    /**
     * Remove item from session (for guest users)
     */
    protected function removeItemFromSession(int $productId, ?int $variantId = null): void
    {
        $cart = Session::get('cart', []);
        $key = $productId . '_' . $variantId;
        unset($cart[$key]);
        Session::put('cart', $cart);
    }

    /**
     * Get items from database (for authenticated users)
     */
    protected function getItemsFromDatabase(): array
    {
        $cartItems = CartItem::with(['product.images', 'variant'])
            ->where('user_id', Auth::id())
            ->get();

        return $cartItems->map(function ($cartItem) {
            $price = $cartItem->product->price;
            if ($cartItem->variant) {
                $price += $cartItem->variant->price_adjustment;
            }

            return [
                'id' => $cartItem->product->id,
                'name' => $cartItem->product->name,
                'slug' => $cartItem->product->slug,
                'price' => $price,
                'quantity' => $cartItem->quantity,
                'subtotal' => $price * $cartItem->quantity,
                'image' => $cartItem->product->images->first()?->image_path,
                'variant_id' => $cartItem->variant?->id,
                'variant_size' => $cartItem->variant?->size,
                'variant_sku' => $cartItem->variant?->sku,
                'variant_stock' => $cartItem->variant?->stock,
            ];
        })->toArray();
    }

    /**
     * Get items from session (for guest users)
     */
    protected function getItemsFromSession(): array
    {
        $cart = Session::get('cart', []);
        $cartItems = [];

        foreach ($cart as $key => $item) {
            $productId = $item['product_id'] ?? null;
            $variantId = $item['variant_id'] ?? null;

            if (!$productId) continue;

            $product = Product::with('images')->find($productId);
            $variant = $variantId ? \App\Models\ProductVariant::find($variantId) : null;

            if ($product) {
                $price = $product->price;
                if ($variant) {
                    $price += $variant->price_adjustment;
                }

                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $price * $item['quantity'],
                    'image' => $product->images->first()?->image_path,
                    'variant_id' => $variant?->id,
                    'variant_size' => $variant?->size,
                    'variant_sku' => $variant?->sku,
                    'variant_stock' => $variant?->stock,
                ];
            }
        }

        return $cartItems;
    }

    /**
     * Apply coupon
     */
    public function applyCoupon(string $code): array
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return ['success' => false, 'message' => 'Kupon tidak ditemukan.'];
        }

        // Get guest_id if exists
        $guestId = request()->cookie('guest_customer_id');

        if (!$coupon->isValidForUser(Auth::id(), $guestId)) {
            return ['success' => false, 'message' => 'Kupon tidak valid atau sudah kadaluarsa.'];
        }

        Session::put('applied_coupon', $coupon->code);

        return ['success' => true, 'message' => 'Kupon berhasil digunakan.', 'coupon' => $coupon];
    }

    /**
     * Remove applied coupon
     */
    public function removeCoupon(): void
    {
        Session::forget('applied_coupon');
    }

    /**
     * Get applied coupon instance
     */
    public function getAppliedCoupon(): ?Coupon
    {
        $code = Session::get('applied_coupon');
        if (!$code) return null;

        return Coupon::where('code', $code)->first();
    }

    /**
     * Calculate discount amount 
     */
    public function getDiscountAmount(float $subtotal): float
    {
        $coupon = $this->getAppliedCoupon();

        if (!$coupon) return 0;

        // Re-validate just in case status changed
        $guestId = request()->cookie('guest_customer_id');
        if (!$coupon->isValidForUser(Auth::id(), $guestId)) {
            $this->removeCoupon();
            return 0;
        }

        return $coupon->calculateDiscount($subtotal);
    }
}
