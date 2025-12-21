<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionService
{
    /**
     * Get orders with filtering and searching
     */
    public function getOrders(array $filters = [])
    {
        $query = Order::with(['user', 'items.product'])
            ->recent();

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Search
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Date range filter
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get order statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'confirmed_orders' => Order::where('status', Order::STATUS_CONFIRMED)->count(),
            'payment_pending' => Order::where('status', Order::STATUS_PAYMENT_PENDING)->count(),
            'payment_confirmed' => Order::where('status', Order::STATUS_PAYMENT_CONFIRMED)->count(),
            'processing_orders' => Order::where('status', Order::STATUS_PROCESSING)->count(),
            'shipped_orders' => Order::where('status', Order::STATUS_SHIPPED)->count(),
            'completed_orders' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'cancelled_orders' => Order::where('status', Order::STATUS_CANCELLED)->count(),
            'total_revenue' => Order::whereIn('status', [
                Order::STATUS_PAYMENT_CONFIRMED,
                Order::STATUS_PROCESSING,
                Order::STATUS_SHIPPED,
                Order::STATUS_DELIVERED,
                Order::STATUS_COMPLETED
            ])->sum('total'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('created_at', today())
                ->whereIn('status', [
                    Order::STATUS_PAYMENT_CONFIRMED,
                    Order::STATUS_PROCESSING,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_DELIVERED,
                    Order::STATUS_COMPLETED
                ])->sum('total'),
        ];
    }

    /**
     * Create a new order
     */
    public function createOrder(array $data, array $items): Order
    {
        DB::beginTransaction();
        try {
            // Generate unique order number
            $data['order_number'] = $this->generateOrderNumber();
            $data['status'] = Order::STATUS_PENDING;

            // Calculate totals
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $data['subtotal'] = $subtotal;
            $data['total'] = $subtotal + ($data['shipping_cost'] ?? 0) - ($data['discount'] ?? 0);

            // Create order
            $order = Order::create($data);

            // Create order items and deduct stock
            foreach ($items as $item) {
                $item['order_id'] = $order->id;
                $item['subtotal'] = $item['price'] * $item['quantity'];
                OrderItem::create($item);

                // Deduct stock from variant if variant_id is present
                if (isset($item['product_variant_id']) && $item['product_variant_id']) {
                    $variant = \App\Models\ProductVariant::find($item['product_variant_id']);
                    if ($variant) {
                        $variant->decrement('stock', $item['quantity']);

                        // Mark as unavailable if stock is 0
                        if ($variant->stock <= 0) {
                            $variant->update(['is_available' => false]);
                        }
                    }
                }
            }

            DB::commit();
            return $order->load('items');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Order $order, string $status, ?string $notes = null): Order
    {
        DB::beginTransaction();
        try {
            $order->status = $status;

            if ($notes) {
                $order->admin_notes = $notes;
            }

            $order->save();

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update order shipping information
     */
    public function updateShipping(Order $order, array $data): Order
    {
        DB::beginTransaction();
        try {
            $order->update([
                'courier' => $data['courier'] ?? $order->courier,
                'tracking_number' => $data['tracking_number'] ?? $order->tracking_number,
                'shipping_cost' => $data['shipping_cost'] ?? $order->shipping_cost,
            ]);

            // Recalculate total if shipping cost changed
            if (isset($data['shipping_cost'])) {
                $order->total = $order->subtotal + $order->shipping_cost - $order->discount;
                $order->save();
            }

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update order customer information
     */
    public function updateCustomerInfo(Order $order, array $data): Order
    {
        DB::beginTransaction();
        try {
            $order->update([
                'customer_name' => $data['customer_name'] ?? $order->customer_name,
                'customer_whatsapp' => $data['customer_whatsapp'] ?? $order->customer_whatsapp,
                'customer_email' => $data['customer_email'] ?? $order->customer_email,
                'shipping_address' => $data['shipping_address'] ?? $order->shipping_address,
                'shipping_city' => $data['shipping_city'] ?? $order->shipping_city,
                'shipping_province' => $data['shipping_province'] ?? $order->shipping_province,
                'shipping_postal_code' => $data['shipping_postal_code'] ?? $order->shipping_postal_code,
                'shipping_notes' => $data['shipping_notes'] ?? $order->shipping_notes,
            ]);

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cancel order
     */
    public function cancelOrder(Order $order, ?string $reason = null): Order
    {
        return $this->updateStatus($order, Order::STATUS_CANCELLED, $reason);
    }

    /**
     * Mark order as shipped
     */
    public function markAsShipped(Order $order, string $courier, string $trackingNumber): Order
    {
        DB::beginTransaction();
        try {
            $order->update([
                'status' => Order::STATUS_SHIPPED,
                'courier' => $courier,
                'tracking_number' => $trackingNumber,
            ]);

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Mark order as completed
     */
    public function markAsCompleted(Order $order): Order
    {
        return $this->updateStatus($order, Order::STATUS_COMPLETED);
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'MHR-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Get recent orders
     */
    public function getRecentOrders(int $limit = 10)
    {
        return Order::with(['user', 'items'])
            ->recent()
            ->limit($limit)
            ->get();
    }

    /**
     * Get order by order number
     */
    public function getOrderByNumber(string $orderNumber): ?Order
    {
        return Order::with(['user', 'items.product'])
            ->where('order_number', $orderNumber)
            ->first();
    }

    /**
     * Delete order (soft delete)
     */
    public function deleteOrder(Order $order): bool
    {
        try {
            return $order->delete();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Update order notes
     */
    public function updateNotes(Order $order, string $notes): Order
    {
        $order->admin_notes = $notes;
        $order->save();
        return $order;
    }
}
