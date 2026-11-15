<script type="application/json" id="moment-media-items">{!! json_encode($media->map(fn($image)=>['id'=>$image->id,'path'=>$image->path,'url'=>\App\Services\MomentContent::imageUrl($image->path),'alt'=>$image->alt_text,'name'=>basename($image->path)])->values(), JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}</script>
<dialog id="moment-media-library" aria-labelledby="moment-media-title" class="m-auto w-[calc(100%_-_24px)] max-w-5xl rounded-xl border-0 bg-white p-0 text-gray-800 shadow-xl backdrop:bg-gray-950/60 dark:bg-neutral-800 dark:text-neutral-200">
    <div class="flex h-[85dvh] max-h-[800px] flex-col">
        <header class="flex items-start justify-between gap-4 border-b border-gray-200 px-5 py-4 dark:border-neutral-700">
            <div><h2 id="moment-media-title" class="font-semibold">Pilih foto</h2><p class="mt-1 text-xs text-gray-500 dark:text-neutral-400" data-library-context></p></div>
            <button type="button" data-library-cancel aria-label="Tutup galeri" class="flex size-9 shrink-0 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 dark:hover:bg-neutral-700"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 6 12 12M6 18 18 6"/></svg></button>
        </header>
        <div class="min-h-0 flex-1 overflow-y-auto p-5" data-library-scroll>
            <div class="flex flex-col gap-3 sm:flex-row">
                <label class="min-w-0 flex-1"><span class="sr-only">Cari foto</span><input type="search" data-library-search placeholder="Cari nama atau deskripsi foto…" autofocus class="block w-full rounded-lg border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200"></label>
                <button type="button" data-library-upload-toggle class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium hover:bg-gray-50 dark:border-neutral-700 dark:hover:bg-neutral-700">＋ Upload foto</button>
            </div>
            <form data-library-upload action="{{ route('admin.moment.media.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-neutral-700 dark:bg-neutral-900" hidden>
                @csrf
                <p class="text-sm font-medium">Upload foto baru</p><p class="text-xs text-gray-500 dark:text-neutral-400">JPG, PNG, WebP. Maksimal 5 MB.</p>
                <label class="block text-sm">File foto<input type="file" name="image" accept="image/jpeg,image/png,image/webp" required class="mt-1 block w-full rounded-lg border border-gray-200 text-sm file:mr-3 file:border-0 file:bg-gray-100 file:px-3 file:py-2 dark:border-neutral-700 dark:file:bg-neutral-700"></label>
                <label class="block text-sm">Deskripsi foto<input name="alt_text" required maxlength="255" class="mt-1 block w-full rounded-lg border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800"></label>
                <button type="submit" data-library-upload-submit class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50">Upload foto</button>
            </form>
            <p role="status" aria-live="polite" class="mt-3 text-sm text-blue-700 dark:text-blue-400" data-library-status></p>
            <p role="alert" class="mt-3 rounded-lg bg-red-50 p-3 text-sm text-red-700 dark:bg-red-950/40 dark:text-red-300" data-library-error hidden></p>
            <p class="my-3 text-xs text-gray-500 dark:text-neutral-400" data-library-count aria-live="polite"></p>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4" data-library-grid></div>
            <div class="py-12 text-center" data-library-empty hidden><p class="text-sm text-gray-500 dark:text-neutral-400">Tidak ada foto yang cocok. Coba kata lain atau upload foto baru.</p><button type="button" data-library-upload-toggle class="mt-3 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Upload foto baru</button></div>
        </div>
        <footer class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 px-5 py-4 dark:border-neutral-700">
            <button type="button" data-library-clear class="text-sm text-gray-500 hover:text-red-600 dark:text-neutral-400">Kosongkan pilihan</button>
            <div class="flex gap-2"><button type="button" data-library-cancel class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium hover:bg-gray-50 disabled:opacity-50 dark:border-neutral-700 dark:hover:bg-neutral-700">Batal</button><button type="button" data-library-apply class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50">Gunakan foto</button></div>
        </footer>
    </div>
</dialog>
