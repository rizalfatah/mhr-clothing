@extends('admin.layouts.app')

@section('title', 'Dasboard')
@section('breadcrumb', 'Dasboard')

@section('content')
    <!-- Page Heading -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">Dasboard</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">Selamat datang kembali, {{ auth()->user()->name }}!
            </p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mt-6">
        <!-- Card: Total Products -->
        <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                        Total Produk
                    </p>
                </div>

                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                        {{ $stats['total_products'] }}
                    </h3>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <!-- Card: Active Products -->
        <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                        Produk Aktif
                    </p>
                </div>

                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                        {{ $stats['active_products'] }}
                    </h3>
                    <span
                        class="inline-flex items-center gap-x-1 py-0.5 px-2 rounded-full bg-green-100 text-green-900 dark:bg-green-800 dark:text-green-100">
                        <svg class="inline-block size-4 self-center" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                            <polyline points="16 7 22 7 22 13" />
                        </svg>
                        <span class="inline-block text-xs font-medium">Aktif</span>
                    </span>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <!-- Card: Categories -->
        <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                        Total Kategori
                    </p>
                </div>

                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                        {{ $stats['total_categories'] }}
                    </h3>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <!-- Card: Featured Products -->
        <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                        Produk Unggulan
                    </p>
                </div>

                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                        {{ $stats['featured_products'] }}
                    </h3>
                    <span
                        class="inline-flex items-center gap-x-1 py-0.5 px-2 rounded-full bg-yellow-100 text-yellow-900 dark:bg-yellow-800 dark:text-yellow-100">
                        <svg class="inline-block size-4 self-center" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                        </svg>
                        <span class="inline-block text-xs font-medium">Unggulan</span>
                    </span>
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
    <!-- End Stats Grid -->

    <!-- Recent Products -->
    <div class="mt-6">
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                        <!-- Header -->
                        <div
                            class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                    Produk Terbaru
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-neutral-400">
                                    Produk terbaru yang ditambahkan ke toko Anda
                                </p>
                            </div>

                            <div>
                                <a href="{{ route('admin.products.index') }}"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                                    Lihat Semua
                                </a>
                            </div>
                        </div>
                        <!-- End Header -->

                        <!-- Table -->
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                            <thead class="bg-gray-50 dark:bg-neutral-800">
                                <tr>
                                    <th scope="col" class="ps-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Produk</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Kategori</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Harga</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Status</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-end">
                                        <span
                                            class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Aksi</span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                @forelse($recent_products as $product)
                                    <tr>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="ps-6 py-3">
                                                <div class="flex items-center gap-x-3">
                                                    @if ($product->primaryImage)
                                                        <img class="inline-block size-[38px] rounded-lg object-cover"
                                                            src="{{ Storage::url($product->primaryImage->image_path) }}"
                                                            alt="{{ $product->name }}">
                                                    @else
                                                        <span
                                                            class="inline-flex items-center justify-center size-[38px] rounded-lg bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-200">
                                                            <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <rect width="18" height="18" x="3" y="3"
                                                                    rx="2" ry="2" />
                                                                <circle cx="9" cy="9" r="2" />
                                                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                                                            </svg>
                                                        </span>
                                                    @endif
                                                    <div class="grow">
                                                        <span
                                                            class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $product->name }}</span>
                                                        <span
                                                            class="block text-sm text-gray-500 dark:text-neutral-500">{{ Str::limit($product->description, 50) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span
                                                    class="text-sm text-gray-600 dark:text-neutral-400">{{ $product->category->name ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">Rp
                                                    {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                @if ($product->is_active)
                                                    <span
                                                        class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                        <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg"
                                                            width="16" height="16" fill="currentColor"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                        </svg>
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span
                                                        class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full dark:bg-white/10 dark:text-white">
                                                        <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg"
                                                            width="16" height="16" fill="currentColor"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                        </svg>
                                                        Nonaktif
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-1.5 flex justify-end gap-x-2">
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                    class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500">
                                                    Edit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3 text-center">
                                                <span class="text-sm text-gray-600 dark:text-neutral-400">Tidak ada
                                                    produk</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- End Table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Recent Products -->
@endsection
