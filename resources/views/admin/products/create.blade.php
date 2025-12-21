@extends('admin.layouts.app')

@section('title', 'Tambah Produk')
@section('breadcrumb', 'Tambah Produk')

@section('content')
    <!-- Page Heading -->
    <div class="mb-6">
        <div class="flex items-center gap-x-3">
            <a href="{{ route('admin.products.index') }}"
                class="inline-flex items-center gap-x-1.5 text-sm text-gray-600 hover:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                Kembali ke Produk
            </a>
        </div>
        <h1 class="mt-2 text-2xl font-semibold text-gray-800 dark:text-neutral-200">Tambah Produk Baru</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">Buat produk baru untuk toko Anda</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="p-4 sm:p-7">
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Informasi Dasar</h2>
                        </div>

                        <div class="space-y-4">
                            <!-- Product Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Nama Produk
                                    <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('name') border-red-500 @enderror"
                                    required>
                                @error('name')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium mb-2 dark:text-white">Kategori
                                    <span class="text-red-500">*</span></label>
                                <select id="category_id" name="category_id"
                                    class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('category_id') border-red-500 @enderror"
                                    required>
                                    <option value="">Pilih kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description"
                                    class="block text-sm font-medium mb-2 dark:text-white">Deskripsi</label>
                                <textarea id="description" name="description" rows="4"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Details -->
                            <div>
                                <label for="details" class="block text-sm font-medium mb-2 dark:text-white">Detail
                                    Produk</label>
                                <textarea id="details" name="details" rows="4"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('details') border-red-500 @enderror"
                                    placeholder="Bahan, petunjuk perawatan, dll.">{{ old('details') }}</textarea>
                                @error('details')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Marketplace Links -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="p-4 sm:p-7">
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Link Marketplace</h2>
                            <p class="text-sm text-gray-600 dark:text-neutral-400">Tambahkan link produk di marketplace</p>
                        </div>

                        <div class="space-y-4">
                            <!-- Tokopedia URL -->
                            <div>
                                <label for="tokopedia_url" class="block text-sm font-medium mb-2 dark:text-white">Link
                                    Tokopedia</label>
                                <input type="url" id="tokopedia_url" name="tokopedia_url"
                                    value="{{ old('tokopedia_url') }}" placeholder="https://tokopedia.com/..."
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('tokopedia_url') border-red-500 @enderror">
                                @error('tokopedia_url')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Shopee URL -->
                            <div>
                                <label for="shopee_url" class="block text-sm font-medium mb-2 dark:text-white">Link
                                    Shopee</label>
                                <input type="url" id="shopee_url" name="shopee_url" value="{{ old('shopee_url') }}"
                                    placeholder="https://shopee.co.id/..."
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('shopee_url') border-red-500 @enderror">
                                @error('shopee_url')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- TikTok URL -->
                            <div>
                                <label for="tiktok_url" class="block text-sm font-medium mb-2 dark:text-white">
                                    Link TikTok Shop</label>
                                <input type="url" id="tiktok_url" name="tiktok_url" value="{{ old('tiktok_url') }}"
                                    placeholder="https://tiktok.com/..."
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('tiktok_url') border-red-500 @enderror">
                                @error('tiktok_url')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Inventory -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="p-4 sm:p-7">
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Harga & Stok</h2>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <!-- Original Price -->
                            <div>
                                <label for="original_price" class="block text-sm font-medium mb-2 dark:text-white">Harga
                                    Asli <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" id="original_price" name="original_price"
                                        value="{{ old('original_price') }}" step="0.01" min="0"
                                        class="py-3 px-4 ps-16 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('original_price') border-red-500 @enderror"
                                        required>
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <span class="text-gray-500 dark:text-neutral-500">Rp</span>
                                    </div>
                                </div>
                                @error('original_price')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Selling Price -->
                            <div>
                                <label for="selling_price" class="block text-sm font-medium mb-2 dark:text-white">Harga
                                    Jual <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" id="selling_price" name="selling_price"
                                        value="{{ old('selling_price') }}" step="0.01" min="0"
                                        class="py-3 px-4 ps-16 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('selling_price') border-red-500 @enderror"
                                        required>
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <span class="text-gray-500 dark:text-neutral-500">Rp</span>
                                    </div>
                                </div>
                                @error('selling_price')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Weight -->
                            <div>
                                <label for="weight" class="block text-sm font-medium mb-2 dark:text-white">Berat (gram)
                                    <span class="text-red-500">*</span></label>
                                <input type="number" id="weight" name="weight" value="{{ old('weight', 0) }}"
                                    min="0"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('weight') border-red-500 @enderror"
                                    required>
                                @error('weight')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sizes & Stock -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="p-4 sm:p-7">
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Ukuran & Stok</h2>
                            <p class="text-sm text-gray-600 dark:text-neutral-400">Kelola stok untuk setiap ukuran produk
                            </p>
                        </div>

                        <div id="variants-container" class="space-y-4">
                            @foreach ($sizes as $index => $size)
                                <div class="variant-row border border-gray-200 rounded-lg p-4 dark:border-neutral-700"
                                    data-index="{{ $index }}">
                                    <div class="grid sm:grid-cols-4 gap-4">
                                        <!-- Size -->
                                        <div>
                                            <label class="block text-sm font-medium mb-2 dark:text-white">Ukuran</label>
                                            <input type="text" name="variants[{{ $index }}][size]"
                                                value="{{ old('variants.' . $index . '.size', $size) }}"
                                                class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                                                readonly>
                                        </div>

                                        <!-- Stock -->
                                        <div>
                                            <label class="block text-sm font-medium mb-2 dark:text-white">Stok <span
                                                    class="text-red-500">*</span></label>
                                            <input type="number" name="variants[{{ $index }}][stock]"
                                                value="{{ old('variants.' . $index . '.stock', 0) }}" min="0"
                                                class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                                                required>
                                        </div>

                                        <!-- SKU -->
                                        <div>
                                            <label class="block text-sm font-medium mb-2 dark:text-white">SKU</label>
                                            <input type="text" name="variants[{{ $index }}][sku]"
                                                value="{{ old('variants.' . $index . '.sku') }}" placeholder="Optional"
                                                class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                        </div>

                                        <!-- Price Adjustment -->
                                        <div>
                                            <label class="block text-sm font-medium mb-2 dark:text-white">Penyesuaian
                                                Harga</label>
                                            <input type="number" name="variants[{{ $index }}][price_adjustment]"
                                                value="{{ old('variants.' . $index . '.price_adjustment', 0) }}"
                                                step="0.01" placeholder="0"
                                                class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                        </div>
                                    </div>

                                    <!-- Is Available -->
                                    <div class="mt-3">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="variants[{{ $index }}][is_available]"
                                                value="1"
                                                {{ old('variants.' . $index . '.is_available', true) ? 'checked' : '' }}
                                                class="shrink-0 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700">
                                            <span class="text-sm text-gray-600 ms-2 dark:text-neutral-400">Tersedia</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Product Images -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="p-4 sm:p-7">
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Gambar Produk</h2>
                            <p class="text-sm text-gray-600 dark:text-neutral-400">Unggah gambar produk (gambar pertama
                                akan menjadi gambar utama)</p>
                        </div>

                        <div>
                            <label for="images" class="block text-sm font-medium mb-2 dark:text-white">Gambar</label>
                            <input type="file" id="images" name="images[]" multiple accept="image/*"
                                class="block w-full border border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 file:bg-gray-50 file:border-0 file:me-4 file:py-3 file:px-4 dark:file:bg-neutral-700 dark:file:text-neutral-400 @if ($errors->has('images') || $errors->has('images.*')) border-red-500 @endif">
                            <p class="mt-2 text-xs text-gray-500 dark:text-neutral-400">PNG, JPG, JPEG, WEBP maksimal 2MB
                            </p>
                            @error('images')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                {{ dump($message) }}

                                {{ dump($errors->get('images.*')) }}
                            @enderror

                            @foreach ($errors->get('images.*') as $messages)
                                @foreach ((array) $messages as $message)
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="p-4 sm:p-7">
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Status</h2>
                        </div>

                        <div class="space-y-3">
                            <!-- Active Status -->
                            <div class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                <label for="is_active" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Aktif
                                    (terlihat di toko)</label>
                            </div>

                            <!-- Featured Status -->
                            <div class="flex items-center">
                                <input type="checkbox" id="is_featured" name="is_featured" value="1"
                                    {{ old('is_featured') ? 'checked' : '' }}
                                    class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                <label for="is_featured" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Produk
                                    unggulan</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="p-4 sm:p-7 space-y-3">
                        <button type="submit"
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                            Buat Produk
                        </button>
                        <a href="{{ route('admin.products.index') }}"
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- End Form -->
@endsection
