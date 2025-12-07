<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display the account page with user's orders
     */
    public function index(Request $request)
    {
        $orders = collect();
        $addresses = collect();

        // Get orders based on authentication status
        if (auth()->check()) {
            // For logged-in users: get orders by user_id
            $orders = Order::with(['items.product'])
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();

            // Load user addresses
            $addresses = auth()->user()->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();
        } else {
            // For guest users: get orders by guest_customer_id from cookie
            $guestCustomerId = $request->cookie('guest_customer_id');

            if ($guestCustomerId) {
                $orders = Order::with(['items.product'])
                    ->where('guest_customer_id', $guestCustomerId)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('account', compact('orders', 'addresses'));
    }
}
