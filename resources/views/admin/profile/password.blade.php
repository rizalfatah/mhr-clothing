@extends('admin.layouts.app')

@section('title', 'Ganti Password')
@section('breadcrumb', 'Ganti Password')

@section('content')
    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Ganti Password</h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">Untuk keamanan, verifikasi email diperlukan sebelum mengubah
            password</p>
    </div>

    <!-- Step Indicator -->
    <div class="mb-6">
        <div class="flex items-center gap-x-2">
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-semibold">1
            </div>
            <div class="text-sm font-medium text-gray-800 dark:text-white">Verifikasi Password Saat Ini</div>
            <div class="flex-1 h-0.5 bg-gray-200 dark:bg-neutral-700 mx-2"></div>
            <div
                class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 dark:bg-neutral-700 text-gray-500 dark:text-neutral-400 text-sm font-semibold">
                2</div>
            <div class="text-sm text-gray-500 dark:text-neutral-400">Verifikasi Email</div>
            <div class="flex-1 h-0.5 bg-gray-200 dark:bg-neutral-700 mx-2"></div>
            <div
                class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 dark:bg-neutral-700 text-gray-500 dark:text-neutral-400 text-sm font-semibold">
                3</div>
            <div class="text-sm text-gray-500 dark:text-neutral-400">Password Baru</div>
        </div>
    </div>

    <!-- Password Form -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-x-2">
                <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="12" cy="16" r="1" />
                    <rect x="3" y="10" width="18" height="12" rx="2" />
                    <path d="M7 10V7a5 5 0 0 1 10 0v3" />
                </svg>
                Verifikasi Password Saat Ini
            </h2>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('admin.profile.password.send-code') }}">
                @csrf

                <div class="mb-4">
                    <label for="current_password"
                        class="block text-sm font-medium mb-2 text-gray-700 dark:text-neutral-300">
                        Password Saat Ini
                    </label>
                    <input type="password" id="current_password" name="current_password"
                        class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                        placeholder="Masukkan password saat ini" required>
                    @error('current_password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <p class="text-sm text-gray-500 dark:text-neutral-400 mb-4">
                    Setelah verifikasi, kode akan dikirim ke email: <strong
                        class="text-gray-700 dark:text-neutral-200">{{ auth()->user()->email }}</strong>
                </p>

                <div class="flex justify-end gap-x-2">
                    <a href="{{ route('admin.dashboard') }}"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                        Batal
                    </a>
                    <button type="submit"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m22 2-7 20-4-9-9-4Z"></path>
                            <path d="M22 2 11 13"></path>
                        </svg>
                        Kirim Kode Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
