@extends('admin.layouts.app')

@section('title', 'Detail Transaksi')
@section('breadcrumb', 'Detail Transaksi')

@section('content')
<!-- Page Heading -->
<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
        <div class="flex items-center gap-x-3">
            <a href="{{ route('admin.transactions.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-200">
                <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"/>
                    <path d="M19 12H5"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">{{ $order->order_number }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">{{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>
    <div class="mt-4 sm:mt-0 flex gap-x-2">
        <a href="{{ route('admin.transactions.invoice', $order) }}" target="_blank" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"/>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                <rect width="12" height="8" x="6" y="14"/>
            </svg>
            Print Invoice
        </a>
        @if($order->status !== 'cancelled' && $order->status !== 'completed')
        <form action="{{ route('admin.transactions.cancel', $order) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan order ini?');">
            @csrf
            @method('PUT')
            <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:bg-red-700">
                Batalkan Order
            </button>
        </form>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Items -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Item Pesanan</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-x-4 pb-4 border-b border-gray-200 last:border-0 dark:border-neutral-700">
                        @if($item->product && $item->product->primaryImage)
                        <img class="size-16 rounded-lg object-cover" src="{{ Storage::url($item->product->primaryImage->image_path) }}" alt="{{ $item->product_name }}">
                        @else
                        <div class="size-16 rounded-lg bg-gray-100 flex items-center justify-center dark:bg-neutral-700">
                            <svg class="size-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                <circle cx="9" cy="9" r="2"/>
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                            </svg>
                        </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800 dark:text-neutral-200">{{ $item->product_name }}</h3>
                            @if($item->variant_name)
                            <p class="text-sm text-gray-500 dark:text-neutral-500">{{ $item->variant_name }}</p>
                            @endif
                            <p class="text-sm text-gray-600 dark:text-neutral-400">Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800 dark:text-neutral-200">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="mt-6 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-neutral-400">Subtotal</span>
                        <span class="text-gray-800 dark:text-neutral-200">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-neutral-400">Ongkir</span>
                        <span class="text-gray-800 dark:text-neutral-200">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-neutral-400">Diskon</span>
                        <span class="text-red-600">-Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-base font-semibold pt-2 border-t border-gray-200 dark:border-neutral-700">
                        <span class="text-gray-800 dark:text-neutral-200">Total</span>
                        <span class="text-gray-800 dark:text-neutral-200">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Informasi Pelanggan</h2>
            </div>
            <div class="p-6">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-500">Nama</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $order->customer_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-500">WhatsApp</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_whatsapp) }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ $order->customer_whatsapp }}
                            </a>
                        </dd>
                    </div>
                    @if($order->customer_email)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $order->customer_email }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-500">Alamat Pengiriman</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                            {{ $order->shipping_address }}<br>
                            {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}
                        </dd>
                    </div>
                    @if($order->shipping_notes)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-500">Catatan Pengiriman</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $order->shipping_notes }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="space-y-6">
        <!-- Status Update -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Update Status</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.transactions.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium mb-2 dark:text-white">Status Saat Ini</label>
                        <div class="py-2 px-3 bg-gray-100 rounded-lg dark:bg-neutral-700">
                            <span class="text-sm font-medium text-{{ $order->status_color }}-600 dark:text-{{ $order->status_color }}-500">
                                {{ $order->status_label }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium mb-2 dark:text-white">Ubah Status</label>
                        <select id="status" name="status" required
                            class="py-2 px-3 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium mb-2 dark:text-white">Catatan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                            placeholder="Tambahkan catatan..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                        Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Informasi Pengiriman</h2>
            </div>
            <div class="p-6">
                @if($order->courier && $order->tracking_number)
                <dl class="space-y-3 mb-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-500">Kurir</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $order->courier }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-500">No. Resi</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $order->tracking_number }}</dd>
                    </div>
                </dl>
                @endif

                <button type="button" 
                    class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                    aria-haspopup="dialog" 
                    aria-expanded="false" 
                    aria-controls="shipping-modal" 
                    data-hs-overlay="#shipping-modal">
                    {{ $order->courier ? 'Update' : 'Tambah' }} Pengiriman
                </button>
            </div>
        </div>

        <!-- Admin Notes -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Catatan Admin</h2>
            </div>
            <div class="p-6">
                @if($order->admin_notes)
                <div class="mb-4 p-3 bg-gray-50 rounded-lg dark:bg-neutral-900">
                    <p class="text-sm text-gray-700 dark:text-neutral-300">{{ $order->admin_notes }}</p>
                </div>
                @endif

                <form action="{{ route('admin.transactions.update-notes', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <textarea id="admin_notes" name="admin_notes" rows="3" required
                            class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                            placeholder="Tambahkan catatan admin...">{{ $order->admin_notes }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                        Simpan Catatan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Shipping Modal -->
<div id="shipping-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-bold text-gray-800 dark:text-white">Update Pengiriman</h3>
                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#shipping-modal">
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <form action="{{ route('admin.transactions.update-shipping', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="courier" class="block text-sm font-medium mb-2 dark:text-white">Kurir</label>
                        <input type="text" id="courier" name="courier" value="{{ $order->courier }}" required
                            class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                            placeholder="JNE, JNT, SiCepat, dll">
                    </div>

                    <div class="mb-4">
                        <label for="tracking_number" class="block text-sm font-medium mb-2 dark:text-white">No. Resi</label>
                        <input type="text" id="tracking_number" name="tracking_number" value="{{ $order->tracking_number }}" required
                            class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                            placeholder="Masukkan nomor resi">
                    </div>

                    <div class="mb-4">
                        <label for="shipping_cost" class="block text-sm font-medium mb-2 dark:text-white">Biaya Kirim (Opsional)</label>
                        <input type="number" id="shipping_cost" name="shipping_cost" value="{{ $order->shipping_cost }}" step="0.01" min="0"
                            class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                            placeholder="0">
                    </div>

                    <div class="flex justify-end gap-x-2">
                        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#shipping-modal">
                            Batal
                        </button>
                        <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
