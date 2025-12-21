@extends('admin.layouts.app')

@section('title', 'Manajemen Stok')
@section('breadcrumb', 'Inventaris Stok')

@section('content')
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">

                    <!-- Header & Filters -->
                    <div
                        class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                Inventaris Stok
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-neutral-400">
                                Kelola stok semua varian produk.
                            </p>
                        </div>

                        <!-- Search -->
                        <div class="sm:col-span-1">
                            <form action="{{ route('admin.stock.index') }}" method="GET">
                                <label for="search" class="sr-only">Search</label>
                                <div class="relative">
                                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                                        class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                        placeholder="Cari Produk / SKU">
                                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                        <svg class="size-4 text-gray-400 dark:text-neutral-500"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8" />
                                            <path d="m21 21-4.3-4.3" />
                                        </svg>
                                    </div>
                                    @if (request('category_id'))
                                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                                    @endif
                                    @if (request('status'))
                                        <input type="hidden" name="status" value="{{ request('status') }}">
                                    @endif
                                    @if (request('sort'))
                                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Filters Bar -->
                    <div
                        class="border-b border-gray-200 py-3 px-6 dark:border-neutral-700 flex flex-wrap gap-2 items-center justify-between">
                        <div class="flex flex-wrap gap-2">
                            <!-- Category Filter -->
                            <div class="hs-dropdown relative inline-flex">
                                <button id="hs-dropdown-category" type="button"
                                    class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                                    @php
                                        $activeCategory = $categories->firstWhere('id', request('category_id'));
                                    @endphp
                                    <span class="font-normal">Kategori:</span>
                                    <span
                                        class="font-semibold">{{ $activeCategory ? $activeCategory->name : 'Semua' }}</span>
                                    <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>
                                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 z-10"
                                    aria-labelledby="hs-dropdown-category">
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300"
                                        href="{{ route('admin.stock.index', array_merge(request()->query(), ['category_id' => null])) }}">
                                        Semua Kategori
                                    </a>
                                    @foreach ($categories as $category)
                                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 {{ request('category_id') == $category->id ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                            href="{{ route('admin.stock.index', array_merge(request()->query(), ['category_id' => $category->id])) }}">
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="hs-dropdown relative inline-flex">
                                <button id="hs-dropdown-status" type="button"
                                    class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                                    @php
                                        $statusLabel = match (request('status')) {
                                            'low_stock' => 'Menipis',
                                            'out_of_stock' => 'Habis',
                                            'in_stock' => 'Tersedia',
                                            default => 'Semua',
                                        };
                                    @endphp
                                    <span class="font-normal">Status:</span>
                                    <span class="font-semibold">{{ $statusLabel }}</span>
                                    <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>
                                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 z-10"
                                    aria-labelledby="hs-dropdown-status">
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300"
                                        href="{{ route('admin.stock.index', array_merge(request()->query(), ['status' => null])) }}">
                                        Semua Status
                                    </a>
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 {{ request('status') == 'low_stock' ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                        href="{{ route('admin.stock.index', array_merge(request()->query(), ['status' => 'low_stock'])) }}">
                                        Stok Menipis (< 5) </a>
                                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 {{ request('status') == 'out_of_stock' ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                                href="{{ route('admin.stock.index', array_merge(request()->query(), ['status' => 'out_of_stock'])) }}">
                                                Habis (0)
                                            </a>
                                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 {{ request('status') == 'in_stock' ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                                href="{{ route('admin.stock.index', array_merge(request()->query(), ['status' => 'in_stock'])) }}">
                                                Tersedia (>= 5)
                                            </a>
                                </div>
                            </div>
                        </div>

                        <!-- Sort Dropdown -->
                        <div class="hs-dropdown relative inline-flex">
                            <button id="hs-dropdown-sort" type="button"
                                class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m3 16 4 4 4-4" />
                                    <path d="M7 20V4" />
                                    <path d="m21 8-4-4-4 4" />
                                    <path d="M17 4v16" />
                                </svg>
                                @php
                                    $sortLabel = match (request('sort', 'stock_asc')) {
                                        'stock_asc' => 'Stok Terendah',
                                        'stock_desc' => 'Stok Tertinggi',
                                        'name_asc' => 'Nama A-Z',
                                        'name_desc' => 'Nama Z-A',
                                        default => 'Stok Terendah',
                                    };
                                @endphp
                                <span class="font-semibold">{{ $sortLabel }}</span>
                            </button>
                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 z-10"
                                aria-labelledby="hs-dropdown-sort">
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 {{ request('sort') == 'stock_asc' ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                    href="{{ route('admin.stock.index', array_merge(request()->query(), ['sort' => 'stock_asc'])) }}">
                                    Stok Terendah
                                </a>
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 {{ request('sort') == 'stock_desc' ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                    href="{{ route('admin.stock.index', array_merge(request()->query(), ['sort' => 'stock_desc'])) }}">
                                    Stok Tertinggi
                                </a>
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 {{ request('sort') == 'name_asc' ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                    href="{{ route('admin.stock.index', array_merge(request()->query(), ['sort' => 'name_asc'])) }}">
                                    Nama Produk (A-Z)
                                </a>
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 {{ request('sort') == 'name_desc' ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                    href="{{ route('admin.stock.index', array_merge(request()->query(), ['sort' => 'name_desc'])) }}">
                                    Nama Produk (Z-A)
                                </a>
                            </div>
                        </div>
                    </div>

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
                                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Varian
                                        / SKU</span>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <span
                                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Stok</span>
                                </th>
                                <th scope="col" class="px-6 py-3 text-end">
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
                            @forelse($stocks as $variant)
                                <tr>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="ps-6 py-3">
                                            <div class="flex items-center gap-x-3">
                                                @if ($variant->product->primaryImage)
                                                    <img class="inline-block size-[38px] rounded-lg object-cover"
                                                        src="{{ Storage::url($variant->product->primaryImage->image_path) }}"
                                                        alt="{{ $variant->product->name }}">
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
                                                        class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $variant->product->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span
                                                class="text-sm text-gray-600 dark:text-neutral-400">{{ $variant->product->category->name }}</span>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <div class="flex flex-col">
                                                <span
                                                    class="block text-sm font-medium text-gray-800 dark:text-neutral-200">Size:
                                                    {{ $variant->size }}</span>
                                                <span class="block text-xs text-gray-500 dark:text-neutral-500">SKU:
                                                    {{ $variant->sku ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <form action="{{ route('admin.stock.update', $variant->id) }}" method="POST"
                                                class="flex items-center gap-2 update-stock-form">
                                                @csrf
                                                @method('PUT')
                                                <div class="relative w-24">
                                                    <input type="number" name="stock" value="{{ $variant->stock }}"
                                                        min="0"
                                                        class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                                </div>
                                                <button type="submit"
                                                    class="p-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent text-blue-600 hover:bg-blue-100 focus:outline-none focus:bg-blue-100 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:bg-blue-800/30 dark:focus:bg-blue-800/30">
                                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path
                                                            d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                                        <polyline points="17 21 17 13 7 13 7 21" />
                                                        <polyline points="7 3 7 8 15 8" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            @if ($variant->stock == 0)
                                                <span
                                                    class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500">
                                                    Habis
                                                </span>
                                            @elseif($variant->stock < 5)
                                                <span
                                                    class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-500/10 dark:text-yellow-500">
                                                    Menipis
                                                </span>
                                            @else
                                                <span
                                                    class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                    Tersedia
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-1.5 flex justify-end gap-x-2">
                                            <a href="{{ route('admin.products.edit', $variant->product_id) }}"
                                                class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500">
                                                Edit Produk
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3 text-center">
                                            <span class="text-sm text-gray-600 dark:text-neutral-400">Tidak ada data stok
                                                ditemukan</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- End Table -->

                    <!-- Footer -->
                    <div
                        class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-neutral-400">
                                <span
                                    class="font-semibold text-gray-800 dark:text-neutral-200">{{ $stocks->total() }}</span>
                                variants
                            </p>
                        </div>

                        <div>
                            {{ $stocks->links() }}
                        </div>
                    </div>
                    <!-- End Footer -->
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const forms = document.querySelectorAll('.update-stock-form');

                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const url = this.getAttribute('action');
                        const formData = new FormData(this);
                        const button = this.querySelector('button[type="submit"]');

                        // Visual feedback (disable button)
                        button.disabled = true;

                        fetch(url, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                button.disabled = false;

                                if (data.success) {
                                    showToast(data.message, 'success');
                                } else {
                                    showToast(data.message || 'Gagal memperbarui stok.', 'error');
                                }
                            })
                            .catch(error => {
                                button.disabled = false;
                                console.error('Error:', error);
                                showToast('Terjadi kesalahan koneksi.', 'error');
                            });
                    });
                });
            });
        </script>
    @endpush
@endsection
