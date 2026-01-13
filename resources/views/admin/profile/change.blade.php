@extends('admin.layouts.app')

@section('title', 'Password Baru')
@section('breadcrumb', 'Password Baru')

@section('content')
    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Ganti Password</h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">Masukkan password baru untuk akun Anda</p>
    </div>

    <!-- Step Indicator -->
    <div class="mb-6">
        <div class="flex items-center gap-x-2">
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-500 text-white text-sm font-semibold">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <div class="text-sm text-gray-500 dark:text-neutral-400">Verifikasi Password</div>
            <div class="flex-1 h-0.5 bg-green-500 mx-2"></div>
            <div
                class="flex items-center justify-center w-8 h-8 rounded-full bg-green-500 text-white text-sm font-semibold">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <div class="text-sm text-gray-500 dark:text-neutral-400">Verifikasi Email</div>
            <div class="flex-1 h-0.5 bg-blue-600 mx-2"></div>
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-semibold">
                3</div>
            <div class="text-sm font-medium text-gray-800 dark:text-white">Password Baru</div>
        </div>
    </div>

    <!-- New Password Form -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-x-2">
                <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z" />
                    <circle cx="16.5" cy="7.5" r=".5" fill="currentColor" />
                </svg>
                Buat Password Baru
            </h2>
        </div>

        <div class="p-6">
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 dark:text-green-300">
                            Email berhasil diverifikasi! Silakan buat password baru untuk akun Anda.
                        </p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.profile.password.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium mb-2 text-gray-700 dark:text-neutral-300">
                            Password Baru
                        </label>
                        <input type="password" id="password" name="password"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                            placeholder="Minimal 8 karakter" required>
                        @error('password')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1 dark:text-neutral-500">Password harus minimal 8 karakter</p>
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium mb-2 text-gray-700 dark:text-neutral-300">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                            placeholder="Ulangi password baru" required>
                    </div>
                </div>

                <div class="flex justify-end gap-x-2 mt-6">
                    <a href="{{ route('admin.dashboard') }}"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                        Batal
                    </a>
                    <button type="submit"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
