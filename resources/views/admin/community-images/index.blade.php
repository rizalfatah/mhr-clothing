@extends('admin.layouts.app')

@section('title', 'Galeri Komunitas')
@section('breadcrumb', 'Galeri Komunitas')

@section('content')
    <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">Galeri Komunitas</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">
            Unggah dan atur gambar yang tampil pada halaman Community.
        </p>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300">
            <p class="font-semibold">Periksa kembali data berikut:</p>
            <ul class="mt-2 list-disc space-y-1 ps-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(320px,0.8fr)_minmax(0,2fr)] xl:items-start">
        <section class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-neutral-700">
                <h2 class="font-semibold text-gray-800 dark:text-white">Tambah Gambar Baru</h2>
            </div>

            <form action="{{ route('admin.community-images.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4 p-6">
                @csrf

                <div>
                    <label for="image" class="mb-2 block text-sm font-medium text-gray-700 dark:text-neutral-300">
                        File gambar
                    </label>
                    <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" required
                        class="block w-full rounded-lg border border-gray-200 text-sm text-gray-700 file:me-4 file:border-0 file:bg-gray-100 file:px-4 file:py-3 file:text-sm file:font-medium hover:file:bg-gray-200 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:file:bg-neutral-700 dark:file:text-neutral-200">
                    <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">JPG, PNG, atau WEBP. Maksimal 5MB.</p>
                </div>

                <div>
                    <label for="alt_text" class="mb-2 block text-sm font-medium text-gray-700 dark:text-neutral-300">
                        Teks alternatif
                    </label>
                    <input id="alt_text" name="alt_text" type="text" value="{{ old('alt_text') }}" required
                        maxlength="255" placeholder="Jelaskan isi gambar"
                        class="block w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                </div>

                <div>
                    <label for="caption" class="mb-2 block text-sm font-medium text-gray-700 dark:text-neutral-300">
                        Caption <span class="font-normal text-gray-400">(opsional)</span>
                    </label>
                    <input id="caption" name="caption" type="text" value="{{ old('caption') }}" maxlength="255"
                        placeholder="Contoh: Urban Vibes"
                        class="block w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                </div>

                <div>
                    <label for="sort_order" class="mb-2 block text-sm font-medium text-gray-700 dark:text-neutral-300">
                        Urutan <span class="font-normal text-gray-400">(opsional)</span>
                    </label>
                    <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order') }}"
                        placeholder="Otomatis di posisi terakhir"
                        class="block w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                </div>

                <label class="flex items-center gap-3 text-sm text-gray-700 dark:text-neutral-300">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900">
                    Langsung tampilkan di halaman Community
                </label>

                <button type="submit"
                    class="inline-flex w-full items-center justify-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700 focus:bg-blue-700 focus:outline-none">
                    Unggah Gambar
                </button>
            </form>
        </section>

        <section>
            <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-gray-800 dark:text-white">Gambar Showcase</h2>
                    <p class="text-sm text-gray-500 dark:text-neutral-400">{{ $communityImages->count() }} gambar tersimpan</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('community') }}" target="_blank" rel="noopener"
                        class="inline-flex items-center gap-x-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700">
                        Lihat Halaman
                    </a>

                    @if ($communityImages->isNotEmpty())
                        <form id="community-order-form" action="{{ route('admin.community-images.update-order') }}"
                            method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center gap-x-2 rounded-lg border border-transparent bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700 focus:bg-emerald-700 focus:outline-none">
                                Simpan Semua Urutan
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if ($communityImages->isNotEmpty())
                <p class="mb-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-900 dark:bg-blue-950/40 dark:text-blue-300">
                    Ubah nomor urutan pada setiap gambar, lalu klik <strong>Simpan Semua Urutan</strong> satu kali.
                    Setiap nomor harus berbeda.
                </p>
            @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                @forelse ($communityImages as $communityImage)
                    <article class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                        <div class="relative bg-gray-100 dark:bg-neutral-900">
                            <img src="{{ $communityImage->image_url }}" alt="{{ $communityImage->alt_text }}"
                                width="{{ $communityImage->width }}" height="{{ $communityImage->height }}"
                                class="h-56 w-full object-cover">
                            <span
                                class="absolute end-3 top-3 rounded-full px-2.5 py-1 text-xs font-semibold {{ $communityImage->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                                {{ $communityImage->is_active ? 'Aktif' : 'Disembunyikan' }}
                            </span>
                            <span class="absolute bottom-3 start-3 rounded-md bg-black/70 px-2 py-1 text-xs text-white">
                                {{ $communityImage->width }} × {{ $communityImage->height }}
                            </span>
                        </div>

                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3 dark:border-neutral-700 dark:bg-neutral-800/60">
                            <label for="sort_order_{{ $communityImage->id }}"
                                class="flex items-center justify-between gap-4 text-sm font-medium text-gray-700 dark:text-neutral-300">
                                <span>Urutan galeri</span>
                                <input id="sort_order_{{ $communityImage->id }}"
                                    form="community-order-form"
                                    name="orders[{{ $communityImage->id }}]"
                                    type="number" min="0"
                                    value="{{ old('orders.'.$communityImage->id, $communityImage->sort_order) }}" required
                                    class="block w-24 rounded-lg border-gray-200 text-center text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                            </label>
                        </div>

                        <form action="{{ route('admin.community-images.update', $communityImage) }}" method="POST"
                            enctype="multipart/form-data" class="space-y-3 p-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="alt_text_{{ $communityImage->id }}"
                                    class="mb-1 block text-xs font-medium text-gray-600 dark:text-neutral-400">Teks alternatif</label>
                                <input id="alt_text_{{ $communityImage->id }}" name="alt_text" type="text"
                                    value="{{ $communityImage->alt_text }}" required maxlength="255"
                                    class="block w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                            </div>

                            <div>
                                <label for="caption_{{ $communityImage->id }}"
                                    class="mb-1 block text-xs font-medium text-gray-600 dark:text-neutral-400">Caption</label>
                                <input id="caption_{{ $communityImage->id }}" name="caption" type="text"
                                    value="{{ $communityImage->caption }}" maxlength="255"
                                    class="block w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                            </div>

                            <div>
                                <label for="image_{{ $communityImage->id }}"
                                    class="mb-1 block text-xs font-medium text-gray-600 dark:text-neutral-400">
                                    Ganti gambar <span class="font-normal text-gray-400">(opsional)</span>
                                </label>
                                <input id="image_{{ $communityImage->id }}" name="image" type="file"
                                    accept="image/jpeg,image/png,image/webp"
                                    class="block w-full rounded-lg border border-gray-200 text-xs text-gray-700 file:me-3 file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-xs file:font-medium hover:file:bg-gray-200 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:file:bg-neutral-700 dark:file:text-neutral-200">
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-neutral-300">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" @checked($communityImage->is_active)
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900">
                                    Aktif
                                </label>

                                <button type="submit"
                                    class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                    Simpan
                                </button>
                            </div>
                        </form>

                        <form action="{{ route('admin.community-images.destroy', $communityImage) }}" method="POST"
                            class="border-t border-gray-200 px-4 py-3 text-end dark:border-neutral-700"
                            onsubmit="return confirm('Hapus gambar ini dari galeri komunitas?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-500">
                                Hapus Gambar
                            </button>
                        </form>
                    </article>
                @empty
                    <div class="md:col-span-2 rounded-xl border border-dashed border-gray-300 p-10 text-center text-sm text-gray-500 dark:border-neutral-700 dark:text-neutral-400">
                        Belum ada gambar komunitas. Unggah gambar pertama melalui formulir.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
