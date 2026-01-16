<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - MHR Admin</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body class="bg-gray-50 dark:bg-neutral-900">

    <!-- Sidebar Toggle (Mobile) -->
    <div
        class="sticky top-0 inset-x-0 z-20 bg-white border-y px-4 sm:px-6 lg:px-8 lg:hidden dark:bg-neutral-800 dark:border-neutral-700">
        <div class="flex items-center py-2">
            <!-- Navigation Toggle -->
            <button type="button"
                class="size-8 flex justify-center items-center gap-x-2 border border-gray-200 text-gray-800 hover:text-gray-500 rounded-lg focus:outline-none focus:text-gray-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:text-neutral-200 dark:hover:text-neutral-500 dark:focus:text-neutral-500"
                aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-application-sidebar"
                aria-label="Toggle navigation" data-hs-overlay="#hs-application-sidebar">
                <span class="sr-only">Toggle Navigation</span>
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="3" rx="2" />
                    <path d="M9 3v18" />
                </svg>
            </button>
            <!-- End Navigation Toggle -->

            <!-- Breadcrumb -->
            <ol class="ms-3 flex items-center whitespace-nowrap">
                <li class="flex items-center text-sm text-gray-800 dark:text-neutral-400">
                    Admin MHR
                    <svg class="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-neutral-500"
                        width="16" height="16" viewBox="0 0 16 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </li>
                <li class="text-sm font-semibold text-gray-800 truncate dark:text-neutral-400" aria-current="page">
                    @yield('breadcrumb', 'Dashboard')
                </li>
            </ol>
            <!-- End Breadcrumb -->
        </div>
    </div>
    <!-- End Sidebar Toggle -->

    <!-- Sidebar -->
    <div id="hs-application-sidebar"
        class="hs-overlay [--auto-close:lg] hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform w-[260px] h-full hidden fixed inset-y-0 start-0 z-[60] bg-white border-e border-gray-200 lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 dark:bg-neutral-800 dark:border-neutral-700"
        role="dialog" tabindex="-1" aria-label="Sidebar">
        <div class="relative flex flex-col h-full max-h-full">
            <!-- Header -->
            <div class="px-6 pt-4">
                <a class="flex-none rounded-xl text-xl inline-block font-semibold focus:outline-none focus:opacity-80"
                    href="{{ route('admin.dashboard') }}" aria-label="MHR">
                    <span class="text-gray-800 dark:text-white">MHR</span> <span class="text-blue-600">Admin</span>
                </a>
            </div>
            <!-- End Header -->

            <!-- Content -->
            <div
                class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
                <nav class="hs-accordion-group p-3 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
                    <ul class="flex flex-col space-y-1">
                        <!-- Dashboard -->
                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                    <polyline points="9 22 9 12 15 12 15 22" />
                                </svg>
                                Dasboard
                            </a>
                        </li>

                        <!-- Products -->
                        <li class="hs-accordion {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                            id="products-accordion">
                            <button type="button"
                                class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.products.*') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                aria-expanded="{{ request()->routeIs('admin.products.*') ? 'true' : 'false' }}"
                                aria-controls="products-accordion-child">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="7" height="7" x="3" y="3" rx="1" />
                                    <rect width="7" height="7" x="14" y="3" rx="1" />
                                    <rect width="7" height="7" x="14" y="14" rx="1" />
                                    <rect width="7" height="7" x="3" y="14" rx="1" />
                                </svg>
                                Produk

                                <svg class="hs-accordion-active:block ms-auto hidden size-4"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m18 15-6-6-6 6" />
                                </svg>

                                <svg class="hs-accordion-active:hidden ms-auto block size-4"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </button>

                            <div id="products-accordion-child"
                                class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 {{ request()->routeIs('admin.products.*') ? '' : 'hidden' }}"
                                role="region" aria-labelledby="products-accordion">
                                <ul class="ps-8 pt-1 space-y-1">
                                    <li>
                                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.products.index') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                            href="{{ route('admin.products.index') }}">
                                            Semua Produk
                                        </a>
                                    </li>
                                    <li>
                                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.products.create') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                            href="{{ route('admin.products.create') }}">
                                            Tambah Produk Baru
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Stock -->
                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.stock.*') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                href="{{ route('admin.stock.index') }}">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                                    <line x1="12" y1="22.08" x2="12" y2="12" />
                                </svg>
                                Inventaris
                            </a>
                        </li>

                        <!-- Coupons -->
                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.coupons.*') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                href="{{ route('admin.coupons.index') }}">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 0 10-9.59 1 1 0 0 1 .696 0 10.75 10.75 0 0 0 9.59 10 1 1 0 0 1 0 .696 10.75 10.75 0 0 0-10 9.59 1 1 0 0 1-.696 0 10.75 10.75 0 0 0-9.59-10" />
                                </svg>
                                Kupon
                            </a>
                        </li>

                        <!-- Categories -->
                        <li class="hs-accordion {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                            id="categories-accordion">
                            <button type="button"
                                class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                aria-expanded="{{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }}"
                                aria-controls="categories-accordion-child">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 7V5a2 2 0 0 1 2-2h2" />
                                    <path d="M17 3h2a2 2 0 0 1 2 2v2" />
                                    <path d="M21 17v2a2 2 0 0 1-2 2h-2" />
                                    <path d="M7 21H5a2 2 0 0 1-2-2v-2" />
                                    <rect width="10" height="8" x="7" y="8" rx="1" />
                                </svg>
                                Kategori

                                <svg class="hs-accordion-active:block ms-auto hidden size-4"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m18 15-6-6-6 6" />
                                </svg>

                                <svg class="hs-accordion-active:hidden ms-auto block size-4"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </button>

                            <div id="categories-accordion-child"
                                class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 {{ request()->routeIs('admin.categories.*') ? '' : 'hidden' }}"
                                role="region" aria-labelledby="categories-accordion">
                                <ul class="ps-8 pt-1 space-y-1">
                                    <li>
                                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.categories.index') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                            href="{{ route('admin.categories.index') }}">
                                            Semua Kategori
                                        </a>
                                    </li>
                                    <li>
                                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.categories.create') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                            href="{{ route('admin.categories.create') }}">
                                            Tambah Kategori Baru
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Transactions -->
                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.transactions.*') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                href="{{ route('admin.transactions.index') }}">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                                    <path d="M3 6h18" />
                                    <path d="M16 10a4 4 0 0 1-8 0" />
                                </svg>
                                Transaksi
                            </a>
                        </li>

                        <!-- User Management -->
                        <li class="hs-accordion {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.invites.*') ? 'active' : '' }}"
                            id="users-accordion">
                            <button type="button"
                                class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.invites.*') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                aria-expanded="{{ request()->routeIs('admin.users.*') || request()->routeIs('admin.invites.*') ? 'true' : 'false' }}"
                                aria-controls="users-accordion-child">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                                Manajemen User

                                <svg class="hs-accordion-active:block ms-auto hidden size-4"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m18 15-6-6-6 6" />
                                </svg>

                                <svg class="hs-accordion-active:hidden ms-auto block size-4"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </button>

                            <div id="users-accordion-child"
                                class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.invites.*') ? '' : 'hidden' }}"
                                role="region" aria-labelledby="users-accordion">
                                <ul class="ps-8 pt-1 space-y-1">
                                    <li>
                                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.users.admins') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                            href="{{ route('admin.users.admins') }}">
                                            Admin
                                        </a>
                                    </li>
                                    <li>
                                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.users.customers') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                            href="{{ route('admin.users.customers') }}">
                                            Customer
                                        </a>
                                    </li>
                                    <li>
                                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.invites.*') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                            href="{{ route('admin.invites.index') }}">
                                            Invite Admin
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Settings -->
                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                                href="{{ route('admin.settings.index') }}">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                Pengaturan
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- End Content -->

            <!-- Footer -->
            <div class="mt-auto">
                <div class="p-3 border-t border-gray-200 dark:border-neutral-700">
                    <!-- Theme Toggle Button -->
                    <button type="button" id="theme-toggle"
                        class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 dark:text-neutral-200 mb-1">
                        <!-- Sun Icon (for light mode) -->
                        <svg id="theme-toggle-light-icon" class="shrink-0 size-4 hidden"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4" />
                            <path d="M12 2v2" />
                            <path d="M12 20v2" />
                            <path d="m4.93 4.93 1.41 1.41" />
                            <path d="m17.66 17.66 1.41 1.41" />
                            <path d="M2 12h2" />
                            <path d="M20 12h2" />
                            <path d="m6.34 17.66-1.41 1.41" />
                            <path d="m19.07 4.93-1.41 1.41" />
                        </svg>
                        <!-- Moon Icon (for dark mode) -->
                        <svg id="theme-toggle-dark-icon" class="shrink-0 size-4 hidden"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                        </svg>
                        <!-- System/Auto Icon (for system preference) -->
                        <svg id="theme-toggle-system-icon" class="shrink-0 size-4 hidden"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" />
                            <line x1="8" x2="16" y1="21" y2="21" />
                            <line x1="12" x2="12" y1="17" y2="21" />
                        </svg>
                        <span id="theme-toggle-text">Mode Gelap</span>
                    </button>

                    <button type="button"
                        class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 dark:text-neutral-200">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 16v-4" />
                            <path d="M12 8h.01" />
                        </svg>
                        <span class="truncate">{{ auth()->user()->name }}</span>
                    </button>
                    <a href="{{ route('admin.profile.index') }}"
                        class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 dark:text-neutral-200 {{ request()->routeIs('admin.profile.*') ? 'bg-gray-100 dark:bg-neutral-700' : '' }}">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        Edit Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/10 dark:text-red-400">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" x2="9" y1="12" y2="12" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
            <!-- End Footer -->
        </div>
    </div>
    <!-- End Sidebar -->

    <!-- Content -->
    <div class="w-full lg:ps-64">
        <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
            <!-- Page Header -->


            @yield('content')
        </div>
    </div>
    <!-- End Content -->

    @stack('scripts')

    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // Helper function for showing toasts
        function showToast(message, type = 'success') {
            Toastify({
                text: message,
                duration: 5000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: type === 'success' ? "#10b981" : "#ef4444", // Tailwind Green-500 or Red-500
                stopOnFocus: true,
            }).showToast();
        }

        // Theme Toggle Functionality
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleSystemIcon = document.getElementById('theme-toggle-system-icon');
        const themeToggleText = document.getElementById('theme-toggle-text');

        // Theme modes: 'light', 'dark', 'system'
        function getCurrentTheme() {
            return localStorage.getItem('color-theme') || 'system';
        }

        function getSystemTheme() {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        function applyTheme(theme) {
            if (theme === 'system') {
                const systemTheme = getSystemTheme();
                document.documentElement.classList.toggle('dark', systemTheme === 'dark');
            } else {
                document.documentElement.classList.toggle('dark', theme === 'dark');
            }
        }

        // Update icons and text based on current theme setting
        function updateThemeUI() {
            const theme = getCurrentTheme();

            // Hide all icons first
            themeToggleLightIcon.classList.add('hidden');
            themeToggleDarkIcon.classList.add('hidden');
            themeToggleSystemIcon.classList.add('hidden');

            // Show appropriate icon and text for the CURRENT active mode
            if (theme === 'light') {
                themeToggleLightIcon.classList.remove('hidden');
                themeToggleText.textContent = 'Mode Terang';
            } else if (theme === 'dark') {
                themeToggleDarkIcon.classList.remove('hidden');
                themeToggleText.textContent = 'Mode Gelap';
            } else { // system
                themeToggleSystemIcon.classList.remove('hidden');
                themeToggleText.textContent = 'Mode Sistem';
            }
        }

        // Cycle through themes: light → dark → system → light
        function cycleTheme() {
            const currentTheme = getCurrentTheme();
            let newTheme;

            if (currentTheme === 'light') {
                newTheme = 'dark';
            } else if (currentTheme === 'dark') {
                newTheme = 'system';
            } else {
                newTheme = 'light';
            }

            localStorage.setItem('color-theme', newTheme);
            applyTheme(newTheme);
            updateThemeUI();
        }

        // Listen for system theme changes (for system mode)
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (getCurrentTheme() === 'system') {
                applyTheme('system');
            }
        });

        // Initialize theme
        applyTheme(getCurrentTheme());
        updateThemeUI();

        // Toggle theme on button click
        themeToggleBtn.addEventListener('click', cycleTheme);

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif

            @if (session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif
        });
    </script>
</body>

</html>
