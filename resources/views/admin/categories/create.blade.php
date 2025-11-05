@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')
@section('breadcrumb', 'Tambah Kategori')

@section('content')
    <!-- Page Heading -->
    <div class="mb-6">
        <div class="flex items-center gap-x-3">
            <a href="{{ route('admin.categories.index') }}"
                class="inline-flex items-center gap-x-1.5 text-sm text-gray-600 hover:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                Kembali ke Kategori
            </a>
        </div>
        <h1 class="mt-2 text-2xl font-semibold text-gray-800 dark:text-neutral-200">Tambah Kategori Baru</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">Buat kategori baru untuk produk Anda</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
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
                            <!-- Category Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Nama Kategori
                                    <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('name') border-red-500 @enderror"
                                    required>
                                @error('name')
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

                            <!-- Sort Order -->
                            <div>
                                <label for="sort_order" class="block text-sm font-medium mb-2 dark:text-white">Urutan
                                    Tampilan</label>
                                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}"
                                    min="0"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('sort_order') border-red-500 @enderror">
                                @error('sort_order')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500 dark:text-neutral-400">Angka lebih kecil akan
                                    ditampilkan lebih dulu</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category Image -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="p-4 sm:p-7">
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Gambar Kategori</h2>
                            <p class="text-sm text-gray-600 dark:text-neutral-400">Unggah gambar untuk kategori</p>
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium mb-2 dark:text-white">Gambar</label>
                            <input type="file" id="image" name="image" accept="image/*"
                                class="block w-full border border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 file:bg-gray-50 file:border-0 file:me-4 file:py-3 file:px-4 dark:file:bg-neutral-700 dark:file:text-neutral-400">
                            <p class="mt-2 text-xs text-gray-500 dark:text-neutral-400">PNG, JPG, JPEG, WEBP maksimal 2MB
                            </p>
                            @error('image')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
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
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                <label for="is_active" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Aktif
                                    (terlihat di toko)</label>
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
                            Buat Kategori
                        </button>
                        <a href="{{ route('admin.categories.index') }}"
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
