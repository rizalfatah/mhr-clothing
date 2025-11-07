@extends('admin.layouts.app')

@section('title', 'Transaksi')
@section('breadcrumb', 'Transaksi')

@section('content')
<!-- Page Heading -->
<div class="sm:flex sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">Manajemen Transaksi</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">Kelola semua transaksi dan pesanan</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Total Orders -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
        <div class="flex items-center gap-x-3">
            <div class="shrink-0">
                <span class="inline-flex items-center justify-center size-12 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-800/30 dark:text-blue-500">
                    <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                        <path d="M3 6h18"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </span>
            </div>
            <div class="grow">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Total Order</p>
                <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">{{ number_format($statistics['total_orders']) }}</h3>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
        <div class="flex items-center gap-x-3">
            <div class="shrink-0">
                <span class="inline-flex items-center justify-center size-12 rounded-lg bg-yellow-100 text-yellow-600 dark:bg-yellow-800/30 dark:text-yellow-500">
                    <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </span>
            </div>
            <div class="grow">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Menunggu</p>
                <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">{{ number_format($statistics['pending_orders']) }}</h3>
            </div>
        </div>
    </div>

    <!-- Processing Orders -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
        <div class="flex items-center gap-x-3">
            <div class="shrink-0">
                <span class="inline-flex items-center justify-center size-12 rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-800/30 dark:text-purple-500">
                    <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </span>
            </div>
            <div class="grow">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Diproses</p>
                <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">{{ number_format($statistics['processing_orders']) }}</h3>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
        <div class="flex items-center gap-x-3">
            <div class="shrink-0">
                <span class="inline-flex items-center justify-center size-12 rounded-lg bg-green-100 text-green-600 dark:bg-green-800/30 dark:text-green-500">
                    <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" x2="12" y1="2" y2="22"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </span>
            </div>
            <div class="grow">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Total Pendapatan</p>
                <h3 class="mt-1 text-xl font-semibold text-gray-800 dark:text-neutral-200">Rp {{ number_format($statistics['total_revenue'], 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="mt-6 bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
    <form method="GET" action="{{ route('admin.transactions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Search -->
        <div>
            <label for="search" class="block text-sm font-medium mb-2 dark:text-white">Cari</label>
            <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}" 
                class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" 
                placeholder="No. Order, Nama, WhatsApp...">
        </div>

        <!-- Status Filter -->
        <div>
            <label for="status" class="block text-sm font-medium mb-2 dark:text-white">Status</label>
            <select id="status" name="status" 
                class="py-2 px-3 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                <option value="">Semua Status</option>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ ($filters['status'] ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <!-- Date From -->
        <div>
            <label for="date_from" class="block text-sm font-medium mb-2 dark:text-white">Dari Tanggal</label>
            <input type="date" id="date_from" name="date_from" value="{{ $filters['date_from'] ?? '' }}" 
                class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
        </div>

        <!-- Date To -->
        <div>
            <label for="date_to" class="block text-sm font-medium mb-2 dark:text-white">Sampai Tanggal</label>
            <input type="date" id="date_to" name="date_to" value="{{ $filters['date_to'] ?? '' }}" 
                class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
        </div>

        <!-- Buttons -->
        <div class="sm:col-span-2 lg:col-span-4 flex gap-x-2">
            <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
                Filter
            </button>
            <a href="{{ route('admin.transactions.index') }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="mt-6 flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-800">
                        <tr>
                            <th scope="col" class="ps-6 py-3 text-start">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">No. Order</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-start">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Pelanggan</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-start">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Total</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-start">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Status</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-start">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Tanggal</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-end">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Aksi</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                            <td class="size-px whitespace-nowrap">
                                <div class="ps-6 py-3">
                                    <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $order->order_number }}</span>
                                </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                                <div class="px-6 py-3">
                                    <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $order->customer_name }}</span>
                                    <span class="block text-xs text-gray-500 dark:text-neutral-500">{{ $order->customer_whatsapp }}</span>
                                </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                                <div class="px-6 py-3">
                                    <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                                <div class="px-6 py-3">
                                    <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-medium bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800 rounded-full dark:bg-{{ $order->status_color }}-500/10 dark:text-{{ $order->status_color }}-500">
                                        {{ $order->status_label }}
                                    </span>
                                </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                                <div class="px-6 py-3">
                                    <span class="text-sm text-gray-600 dark:text-neutral-400">{{ $order->created_at->format('d M Y H:i') }}</span>
                                </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                                <div class="px-6 py-1.5 flex justify-end gap-x-2">
                                    <a href="{{ route('admin.transactions.show', $order) }}" class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="size-px whitespace-nowrap">
                                <div class="px-6 py-8 text-center">
                                    <svg class="mx-auto size-16 text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                                        <path d="M3 6h18"/>
                                        <path d="M16 10a4 4 0 0 1-8 0"/>
                                    </svg>
                                    <p class="mt-4 text-sm text-gray-600 dark:text-neutral-400">Tidak ada transaksi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
