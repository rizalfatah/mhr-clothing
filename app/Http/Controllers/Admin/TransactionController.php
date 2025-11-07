<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Display transaction dashboard
     */
    public function index(Request $request)
    {
        $filters = [
            'status' => $request->input('status'),
            'search' => $request->input('search'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'per_page' => $request->input('per_page', 15),
        ];

        $orders = $this->transactionService->getOrders($filters);
        $statistics = $this->transactionService->getStatistics();
        $statuses = Order::getStatuses();

        return view('admin.transactions.index', compact('orders', 'statistics', 'statuses', 'filters'));
    }

    /**
     * Display order detail
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        $statuses = Order::getStatuses();
        
        return view('admin.transactions.show', compact('order', 'statuses'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatuses())),
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $this->transactionService->updateStatus(
                $order,
                $validated['status'],
                $validated['notes'] ?? null
            );

            return back()->with('success', 'Status order berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Update shipping information
     */
    public function updateShipping(Request $request, Order $order)
    {
        $validated = $request->validate([
            'courier' => 'required|string|max:100',
            'tracking_number' => 'required|string|max:100',
            'shipping_cost' => 'nullable|numeric|min:0',
        ]);

        try {
            $this->transactionService->updateShipping($order, $validated);
            
            // Auto update status to shipped if not already
            if ($order->status !== Order::STATUS_SHIPPED) {
                $this->transactionService->updateStatus($order, Order::STATUS_SHIPPED);
            }

            return back()->with('success', 'Informasi pengiriman berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui informasi pengiriman: ' . $e->getMessage());
        }
    }

    /**
     * Update customer information
     */
    public function updateCustomer(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_whatsapp' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_province' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:10',
            'shipping_notes' => 'nullable|string',
        ]);

        try {
            $this->transactionService->updateCustomerInfo($order, $validated);

            return back()->with('success', 'Informasi pelanggan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui informasi pelanggan: ' . $e->getMessage());
        }
    }

    /**
     * Update order notes
     */
    public function updateNotes(Request $request, Order $order)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        try {
            $this->transactionService->updateNotes($order, $validated['admin_notes']);

            return back()->with('success', 'Catatan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui catatan: ' . $e->getMessage());
        }
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, Order $order)
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->transactionService->cancelOrder($order, $validated['reason'] ?? null);

            return back()->with('success', 'Order berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan order: ' . $e->getMessage());
        }
    }

    /**
     * Delete order
     */
    public function destroy(Order $order)
    {
        try {
            $this->transactionService->deleteOrder($order);

            return redirect()->route('admin.transactions.index')
                ->with('success', 'Order berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus order: ' . $e->getMessage());
        }
    }

    /**
     * Print invoice
     */
    public function printInvoice(Order $order)
    {
        $order->load(['user', 'items.product']);
        
        return view('admin.transactions.invoice', compact('order'));
    }
}
