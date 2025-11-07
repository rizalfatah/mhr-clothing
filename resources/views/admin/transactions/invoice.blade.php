<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-white p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Print Button -->
        <div class="no-print mb-4 flex justify-end">
            <button onclick="window.print()" class="py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Print Invoice
            </button>
        </div>

        <!-- Invoice Header -->
        <div class="border-b-2 border-gray-300 pb-8 mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800">MHR CLOTHING</h1>
                    <p class="mt-2 text-gray-600">Invoice</p>
                </div>
                <div class="text-right">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $order->order_number }}</h2>
                    <p class="mt-2 text-sm text-gray-600">Tanggal: {{ $order->created_at->format('d M Y') }}</p>
                    <p class="mt-1">
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                            {{ $order->status_label }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Customer & Shipping Info -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-sm font-semibold text-gray-800 uppercase mb-3">Pelanggan</h3>
                <p class="text-gray-800 font-medium">{{ $order->customer_name }}</p>
                <p class="text-gray-600 text-sm mt-1">{{ $order->customer_whatsapp }}</p>
                @if($order->customer_email)
                <p class="text-gray-600 text-sm">{{ $order->customer_email }}</p>
                @endif
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800 uppercase mb-3">Alamat Pengiriman</h3>
                <p class="text-gray-800">{{ $order->shipping_address }}</p>
                <p class="text-gray-800">{{ $order->shipping_city }}, {{ $order->shipping_province }}</p>
                <p class="text-gray-800">{{ $order->shipping_postal_code }}</p>
            </div>
        </div>

        <!-- Order Items -->
        <div class="mb-8">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-300">
                        <th class="text-left py-3 text-sm font-semibold text-gray-800 uppercase">Item</th>
                        <th class="text-center py-3 text-sm font-semibold text-gray-800 uppercase">Qty</th>
                        <th class="text-right py-3 text-sm font-semibold text-gray-800 uppercase">Harga</th>
                        <th class="text-right py-3 text-sm font-semibold text-gray-800 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="border-b border-gray-200">
                        <td class="py-4">
                            <p class="font-medium text-gray-800">{{ $item->product_name }}</p>
                            @if($item->variant_name)
                            <p class="text-sm text-gray-600">{{ $item->variant_name }}</p>
                            @endif
                        </td>
                        <td class="py-4 text-center text-gray-800">{{ $item->quantity }}</td>
                        <td class="py-4 text-right text-gray-800">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="py-4 text-right text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Order Summary -->
        <div class="flex justify-end mb-8">
            <div class="w-64">
                <div class="flex justify-between py-2 text-gray-800">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 text-gray-800">
                    <span>Ongkir:</span>
                    <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @if($order->discount > 0)
                <div class="flex justify-between py-2 text-red-600">
                    <span>Diskon:</span>
                    <span>-Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between py-3 border-t-2 border-gray-300 text-lg font-bold text-gray-800">
                    <span>Total:</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Shipping Info -->
        @if($order->courier && $order->tracking_number)
        <div class="border-t border-gray-300 pt-6 mb-8">
            <h3 class="text-sm font-semibold text-gray-800 uppercase mb-3">Informasi Pengiriman</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Kurir:</p>
                    <p class="text-gray-800 font-medium">{{ $order->courier }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">No. Resi:</p>
                    <p class="text-gray-800 font-medium">{{ $order->tracking_number }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Notes -->
        @if($order->admin_notes || $order->shipping_notes)
        <div class="border-t border-gray-300 pt-6">
            <h3 class="text-sm font-semibold text-gray-800 uppercase mb-3">Catatan</h3>
            @if($order->shipping_notes)
            <div class="mb-3">
                <p class="text-sm text-gray-600">Catatan Pengiriman:</p>
                <p class="text-gray-800">{{ $order->shipping_notes }}</p>
            </div>
            @endif
            @if($order->admin_notes)
            <div>
                <p class="text-sm text-gray-600">Catatan Admin:</p>
                <p class="text-gray-800">{{ $order->admin_notes }}</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Footer -->
        <div class="mt-12 text-center text-sm text-gray-600">
            <p>Terima kasih atas pembelian Anda!</p>
            <p class="mt-1">MHR Clothing - Quality Fashion for Everyone</p>
        </div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
