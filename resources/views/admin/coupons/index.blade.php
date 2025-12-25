@extends('admin.layouts.app')

@section('title', 'Manajemen Kupon')
@section('breadcrumb', 'Manajemen Kupon')

@section('content')
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">

                    <!-- Header -->
                    <div
                        class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                Manajemen Kupon
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-neutral-400">
                                Kelola kupon diskon untuk meningkatkan penjualan.
                            </p>
                        </div>

                        <div>
                            <div class="inline-flex gap-x-2">
                                <a href="{{ route('admin.coupons.bulk-generate') }}"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                                        <path
                                            d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                                        <path d="M9 14h6" />
                                        <path d="M9 10h6" />
                                        <path d="M9 18h6" />
                                    </svg>
                                    Bulk Generate
                                </a>
                                <a href="{{ route('admin.coupons.create') }}"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14" />
                                        <path d="M12 5v14" />
                                    </svg>
                                    Tambah Kupon
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
                                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Kode</span>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <span
                                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Tipe</span>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <span
                                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Nilai</span>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <span
                                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Periode</span>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <span
                                        class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Usage</span>
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
                            @forelse($coupons as $coupon)
                                <tr>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="ps-6 py-3">
                                            <span
                                                class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $coupon->code }}</span>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span
                                                class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium rounded-full {{ $coupon->type === 'fixed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-500' : 'bg-purple-100 text-purple-800 dark:bg-purple-500/10 dark:text-purple-500' }}">
                                                {{ $coupon->type === 'fixed' ? 'Potongan Tetap' : 'Persentase' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span
                                                class="text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $coupon->type === 'fixed' ? 'Rp ' . number_format($coupon->value, 0, ',', '.') : $coupon->value . '%' }}</span>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <div class="flex flex-col">
                                                @if ($coupon->start_date)
                                                    <span class="text-xs text-gray-600 dark:text-neutral-400">Mulai:
                                                        {{ $coupon->start_date->format('d M Y') }}</span>
                                                @endif
                                                @if ($coupon->end_date)
                                                    <span class="text-xs text-gray-600 dark:text-neutral-400">Sampai:
                                                        {{ $coupon->end_date->format('d M Y') }}</span>
                                                @endif
                                                @if (!$coupon->start_date && !$coupon->end_date)
                                                    <span
                                                        class="text-sm text-gray-600 dark:text-neutral-400">Selamanya</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">Total:
                                                    {{ $coupon->usages_count ?? 0 }} /
                                                    {{ $coupon->usage_limit ?? '∞' }}</span>
                                                <span class="text-xs text-gray-500 dark:text-neutral-500">Per User:
                                                    {{ $coupon->usage_limit_per_user ?? '∞' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span
                                                class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium rounded-full {{ $coupon->is_active ? 'bg-teal-100 text-teal-800 dark:bg-teal-500/10 dark:text-teal-500' : 'bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-500' }}">
                                                {{ $coupon->is_active ? 'Aktif' : 'Non-aktif' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-1.5 flex justify-end gap-x-2">
                                            <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                                class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kupon ini?');">
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
                                    <td colspan="7" class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3 text-center">
                                            <span class="text-sm text-gray-600 dark:text-neutral-400">Belum ada kupon yang
                                                dibuat</span>
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
                                    class="font-semibold text-gray-800 dark:text-neutral-200">{{ $coupons->total() }}</span>
                                coupons
                            </p>
                        </div>

                        <div>
                            {{ $coupons->links() }}
                        </div>
                    </div>
                    <!-- End Footer -->
                </div>
            </div>
        </div>
    </div>
@endsection
