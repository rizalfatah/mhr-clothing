@extends('admin.layouts.app')

@section('title', 'Produk')
@section('breadcrumb', 'Produk')

@section('content')
<!-- Page Heading -->
<div class="sm:flex sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">Produk</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">Kelola produk toko Anda</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('admin.products.create') }}" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/>
                <path d="M12 5v14"/>
            </svg>
            Tambah Produk
        </a>
    </div>
</div>

<!-- Search Form -->
<div class="mt-6">
    <form method="GET" action="{{ route('admin.products.index') }}" class="flex gap-x-2">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                <svg class="shrink-0 size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" class="py-3 ps-11 pe-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Cari produk berdasarkan nama, kategori, atau deskripsi...">
        </div>
        <button type="submit" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
            Cari
        </button>
        @if(request('search'))
        <a href="{{ route('admin.products.index') }}" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"/>
                <path d="m6 6 12 12"/>
            </svg>
            Reset
        </a>
        @endif
    </form>
    @if(request('search'))
    <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
        Menampilkan hasil pencarian untuk: <span class="font-semibold">{{ request('search') }}</span>
    </p>
    @endif
</div>

<!-- Products Table -->
<div class="mt-6 flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                <!-- Table -->
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-800">
                        <tr>
                            <th scope="col" class="ps-6 py-3 text-start">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Produk</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-start">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Kategori</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-start">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Harga</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-start">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Status</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-start">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Unggulan</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-end">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Aksi</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                        @forelse($products as $product)
                        <tr>
                            <td class="size-px whitespace-nowrap">
                                <div class="ps-6 py-3">
                                    <div class="flex items-center gap-x-3">
                                        @if($product->primaryImage)
                                        <img class="inline-block size-[50px] rounded-lg object-cover" src="{{ Storage::url($product->primaryImage->image_path) }}" alt="{{ $product->name }}">
                                        @else
                                        <span class="inline-flex items-center justify-center size-[50px] rounded-lg bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-200">
                                            <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                                <circle cx="9" cy="9" r="2"/>
                                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                            </svg>
                                        </span>
                                        @endif
                                        <div class="grow">
                                            <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $product->name }}</span>
                                            <span class="block text-sm text-gray-500 dark:text-neutral-500">{{ Str::limit($product->description, 50) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                                <div class="px-6 py-3">
                                    <span class="text-sm text-gray-600 dark:text-neutral-400">{{ $product->category->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                                <div class="px-6 py-3">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                                        @if($product->original_price > $product->selling_price)
                                        <span class="text-xs text-gray-500 line-through dark:text-neutral-500">Rp {{ number_format($product->original_price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                                <div class="px-6 py-3">
                                    @if($product->is_active)
                                    <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                        <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                        </svg>
                                        Aktif
                                    </span>
                                    @else
                                    <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full dark:bg-white/10 dark:text-white">
                                        <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                        </svg>
                                        Nonaktif
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                                <div class="px-6 py-3">
                                    @if($product->is_featured)
                                    <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-800/30 dark:text-yellow-500">
                                        <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                        </svg>
                                        Unggulan
                                    </span>
                                    @else
                                    <span class="text-xs text-gray-400 dark:text-neutral-600">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                                <div class="px-6 py-1.5 flex justify-end gap-x-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-x-1 text-sm text-red-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-red-500">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="size-px whitespace-nowrap">
                                    <div class="px-6 py-8 text-center">
                                        <svg class="mx-auto size-16 text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect width="7" height="7" x="3" y="3" rx="1"/>
                                            <rect width="7" height="7" x="14" y="3" rx="1"/>
                                            <rect width="7" height="7" x="14" y="14" rx="1"/>
                                            <rect width="7" height="7" x="3" y="14" rx="1"/>
                                        </svg>
                                        <p class="mt-4 text-sm text-gray-600 dark:text-neutral-400">Tidak ada produk</p>
                                        <a href="{{ route('admin.products.create') }}" class="mt-4 inline-flex items-center gap-x-2 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-500 dark:hover:text-blue-400">
                                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14"/>
                                                <path d="M12 5v14"/>
                                            </svg>
                                            Tambah produk pertama Anda
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <!-- End Table -->

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
                    {{ $products->links() }}
                </div>
                @endif
                <!-- End Pagination -->
            </div>
        </div>
    </div>
</div>
<!-- End Products Table -->
@endsection
