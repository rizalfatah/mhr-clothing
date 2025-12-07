<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Add item to cart (database or session based on auth status)
     */
    public function addItem(int $productId, int $quantity): void
    {
        if (Auth::check()) {
            $this->addItemToDatabase($productId, $quantity);
        } else {
            $this->addItemToSession($productId, $quantity);
        }
    }

    /**
     * Update item quantity in cart
     */
    public function updateItem(int $productId, int $quantity): void
    {
        if (Auth::check()) {
            $this->updateItemInDatabase($productId, $quantity);
        } else {
            $this->updateItemInSession($productId, $quantity);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeItem(int $productId): void
    {
        if (Auth::check()) {
            $this->removeItemFromDatabase($productId);
        } else {
            $this->removeItemFromSession($productId);
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
    protected function addItemToDatabase(int $productId, int $quantity): void
    {
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }
    }

    /**
     * Add item to session (for guest users)
     */
    protected function addItemToSession(int $productId, int $quantity): void
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'quantity' => $quantity,
            ];
        }

        Session::put('cart', $cart);
    }

    /**
     * Update item in database (for authenticated users)
     */
    protected function updateItemInDatabase(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeItemFromDatabase($productId);
            return;
        }

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
        }
    }

    /**
     * Update item in session (for guest users)
     */
    protected function updateItemInSession(int $productId, int $quantity): void
    {
        $cart = Session::get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $quantity;
            }
        }

        Session::put('cart', $cart);
    }

    /**
     * Remove item from database (for authenticated users)
     */
    protected function removeItemFromDatabase(int $productId): void
    {
        CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();
    }

    /**
     * Remove item from session (for guest users)
     */
    protected function removeItemFromSession(int $productId): void
    {
        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);
    }

    /**
     * Get items from database (for authenticated users)
     */
    protected function getItemsFromDatabase(): array
    {
        $cartItems = CartItem::with('product.images')
            ->where('user_id', Auth::id())
            ->get();

        return $cartItems->map(function ($cartItem) {
            return [
                'id' => $cartItem->product->id,
                'name' => $cartItem->product->name,
                'slug' => $cartItem->product->slug,
                'price' => $cartItem->product->price,
                'quantity' => $cartItem->quantity,
                'subtotal' => $cartItem->product->price * $cartItem->quantity,
                'image' => $cartItem->product->images->first()?->image_path,
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

        foreach ($cart as $productId => $item) {
            $product = Product::with('images')->find($productId);
            if ($product) {
                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity'],
                    'image' => $product->images->first()?->image_path,
                ];
            }
        }

        return $cartItems;
    }
}
