@extends('admin.layouts.app')

@section('title', 'Verify Current Email')
@section('breadcrumb', 'Verify Current Email')

@section('content')
    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Change Email Address</h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">Verify your current email to proceed</p>
    </div>

    <!-- Step Indicator -->
    <div class="mb-6">
        <div class="flex items-center gap-x-2">
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-semibold">1
            </div>
            <div class="text-sm font-medium text-gray-800 dark:text-white">Verify Current Email</div>
            <div class="flex-1 h-0.5 bg-gray-200 dark:bg-neutral-700 mx-2"></div>
            <div
                class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 dark:bg-neutral-700 text-gray-500 dark:text-neutral-400 text-sm font-semibold">
                2</div>
            <div class="text-sm text-gray-500 dark:text-neutral-400">Verify New Email</div>
        </div>
    </div>

    <!-- Verification Form -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-x-2">
                <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <rect width="20" height="16" x="2" y="4" rx="2" />
                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                </svg>
                Enter Verification Code
            </h2>
        </div>

        <div class="p-6">
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            A verification code has been sent to <strong>{{ $currentEmail }}</strong>.
                            This code is valid for 10 minutes.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-600 dark:text-neutral-400">
                    <strong>New email:</strong> <span class="text-gray-800 dark:text-neutral-200">{{ $newEmail }}</span>
                </p>
            </div>

            <form method="POST" action="{{ route('admin.profile.email.verify-old-code') }}">
                @csrf

                <div class="mb-4">
                    <label for="verification_code"
                        class="block text-sm font-medium mb-2 text-gray-700 dark:text-neutral-300">
                        Verification Code (6 digits)
                    </label>
                    <input type="text" id="verification_code" name="verification_code"
                        class="py-3 px-4 block w-full border-gray-200 rounded-lg text-center tracking-[0.5em] font-mono text-xl focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                        placeholder="000000" maxlength="6" required autofocus
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    @error('verification_code')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-gray-500 dark:text-neutral-400">
                        Didn't receive the code?
                    </p>
                    <a href="{{ route('admin.profile.email.resend-old') }}"
                        class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                        Resend Code
                    </a>
                </div>

                <div class="flex justify-end gap-x-2">
                    <a href="{{ route('admin.profile.index') }}"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                        Cancel
                    </a>
                    <button type="submit"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5"></path>
                        </svg>
                        Verify & Continue
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
