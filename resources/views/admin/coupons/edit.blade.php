@extends('admin.layouts.app')

@section('title', 'Edit Kupon')
@section('breadcrumb', 'Edit Kupon')

@section('content')
    <!-- Page Heading -->
    <div class="mb-6">
        <div class="flex items-center gap-x-3">
            <a href="{{ route('admin.coupons.index') }}"
                class="inline-flex items-center gap-x-1.5 text-sm text-gray-600 hover:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                Kembali ke Kupon
            </a>
        </div>
        <h1 class="mt-2 text-2xl font-semibold text-gray-800 dark:text-neutral-200">Edit Kupon</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">Ubah informasi kupon diskon</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="p-4 sm:p-7">
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Informasi Kupon</h2>
                        </div>

                        <div class="space-y-4">
                            <!-- Coupon Code -->
                            <div>
                                <label for="code" class="block text-sm font-medium mb-2 dark:text-white">Kode Kupon
                                    <span class="text-red-500">*</span></label>
                                <input type="text" id="code" name="code" value="{{ old('code', $coupon->code) }}"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm uppercase focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('code') border-red-500 @enderror"
                                    required>
                                @error('code')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid sm:grid-cols-2 gap-4">
                                <!-- Discount Type -->
                                <div>
                                    <label for="type" class="block text-sm font-medium mb-2 dark:text-white">Tipe
                                        Diskon <span class="text-red-500">*</span></label>
                                    <select id="type" name="type"
                                        class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('type') border-red-500 @enderror"
                                        required>
                                        <option value="fixed"
                                            {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>
                                            Potongan Tetap (Rp)</option>
                                        <option value="percent"
                                            {{ old('type', $coupon->type) == 'percent' ? 'selected' : '' }}>Persentase (%)
                                        </option>
                                    </select>
                                    @error('type')
                                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Discount Value -->
                                <div>
                                    <label for="value" class="block text-sm font-medium mb-2 dark:text-white">Nilai
                                        Diskon <span class="text-red-500">*</span></label>
                                    <input type="number" id="value" name="value"
                                        value="{{ old('value', $coupon->value) }}" step="0.01" min="0"
                                        class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('value') border-red-500 @enderror"
                                        required>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-neutral-500">Masukkan jumlah Rupiah atau
                                        persen</p>
                                    @error('value')
                                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Validity Period -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="p-4 sm:p-7">
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Periode Berlaku</h2>
                            <p class="text-sm text-gray-600 dark:text-neutral-400">Tentukan kapan kupon dapat digunakan</p>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium mb-2 dark:text-white">Tanggal
                                    Mulai</label>
                                <input type="date" id="start_date" name="start_date"
                                    value="{{ old('start_date', $coupon->start_date?->format('Y-m-d')) }}"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('start_date') border-red-500 @enderror">
                                @error('start_date')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium mb-2 dark:text-white">Tanggal
                                    Berakhir</label>
                                <input type="date" id="end_date" name="end_date"
                                    value="{{ old('end_date', $coupon->end_date?->format('Y-m-d')) }}"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('end_date') border-red-500 @enderror">
                                @error('end_date')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-neutral-500">Kosongkan jika kupon tidak memiliki
                            batasan waktu</p>
                    </div>
                </div>

                <!-- Usage Limits -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="p-4 sm:p-7">
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Batas Penggunaan</h2>
                            <p class="text-sm text-gray-600 dark:text-neutral-400">Batasi berapa kali kupon dapat digunakan
                            </p>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <!-- Total Usage Limit -->
                            <div>
                                <label for="usage_limit" class="block text-sm font-medium mb-2 dark:text-white">Batas
                                    Penggunaan Total</label>
                                <input type="number" id="usage_limit" name="usage_limit"
                                    value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('usage_limit') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500 dark:text-neutral-500">Kosongkan jika tidak terbatas
                                </p>
                                @error('usage_limit')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Per User Usage Limit -->
                            <div>
                                <label for="usage_limit_per_user"
                                    class="block text-sm font-medium mb-2 dark:text-white">Batas Penggunaan Per User</label>
                                <input type="number" id="usage_limit_per_user" name="usage_limit_per_user"
                                    value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user) }}"
                                    min="1"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('usage_limit_per_user') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500 dark:text-neutral-500">Kosongkan jika tidak terbatas
                                </p>
                                @error('usage_limit_per_user')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Usage Statistics -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg dark:bg-neutral-900">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-200 mb-2">Statistik Penggunaan
                            </h3>
                            <div class="flex items-center gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600 dark:text-neutral-400">Total Digunakan:</span>
                                    <span
                                        class="font-semibold text-gray-800 dark:text-neutral-200 ml-1">{{ $coupon->usages_count ?? 0 }}</span>
                                </div>
                            </div>
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
                                    {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}
                                    class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                <label for="is_active" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Aktif
                                    (kupon dapat digunakan)</label>
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
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                <polyline points="17 21 17 13 7 13 7 21" />
                                <polyline points="7 3 7 8 15 8" />
                            </svg>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.coupons.index') }}"
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
