@extends('admin.layouts.app')

@section('title', 'Kategori')
@section('breadcrumb', 'Kategori')

@section('content')
    <!-- Page Heading -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">Kategori</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">Kelola kategori produk toko Anda</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.categories.create') }}"
                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
                Tambah Kategori
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="mt-6">
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                        <!-- Table -->
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                            <thead class="bg-gray-50 dark:bg-neutral-800">
                                <tr>
                                    <th scope="col" class="ps-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Kategori</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Deskripsi</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Jumlah
                                            Produk</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Urutan</span>
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
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="ps-6 py-3">
                                                <div class="flex items-center gap-x-3">
                                                    @if ($category->image)
                                                        <img class="inline-block size-[38px] rounded-lg object-cover"
                                                            src="{{ Storage::url($category->image) }}"
                                                            alt="{{ $category->name }}">
                                                    @else
                                                        <span
                                                            class="inline-flex items-center justify-center size-[38px] rounded-lg bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-200">
                                                            <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M3 7V5a2 2 0 0 1 2-2h2" />
                                                                <path d="M17 3h2a2 2 0 0 1 2 2v2" />
                                                                <path d="M21 17v2a2 2 0 0 1-2 2h-2" />
                                                                <path d="M7 21H5a2 2 0 0 1-2-2v-2" />
                                                                <rect width="10" height="8" x="7" y="8"
                                                                    rx="1" />
                                                            </svg>
                                                        </span>
                                                    @endif
                                                    <div class="grow">
                                                        <span
                                                            class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $category->name }}</span>
                                                        <span
                                                            class="block text-sm text-gray-500 dark:text-neutral-500">{{ $category->slug }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span
                                                    class="text-sm text-gray-600 dark:text-neutral-400">{{ Str::limit($category->description, 50) ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span
                                                    class="text-sm text-gray-800 dark:text-neutral-200">{{ $category->products_count }}</span>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span
                                                    class="text-sm text-gray-800 dark:text-neutral-200">{{ $category->sort_order }}</span>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                @if ($category->is_active)
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
                                                <a href="{{ route('admin.categories.edit', $category) }}"
                                                    class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.categories.destroy', $category) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-x-1 text-sm text-red-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-red-500">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3 text-center">
                                                <span class="text-sm text-gray-600 dark:text-neutral-400">Tidak ada
                                                    kategori</span>
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
    <!-- End Table Card -->
@endsection
