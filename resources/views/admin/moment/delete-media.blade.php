<details class="group" data-media-delete>
    <summary class="inline-flex cursor-pointer list-none items-center gap-x-2 rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 dark:text-red-400 dark:hover:bg-red-500/10 [&::-webkit-details-marker]:hidden">
        <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 6h18M9 6V4h6v2M5 6l1 14h12l1-14M10 10v6M14 10v6" />
        </svg>
        Hapus jika tidak digunakan
    </summary>
    <div class="mt-2 rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-950/40">
        <p id="delete-media-question-{{ $image->id }}" class="text-sm font-medium text-gray-800 dark:text-neutral-200">Yakin ingin menghapus media ini?</p>
        <p class="mt-1 break-all text-xs text-gray-600 dark:text-neutral-400">{{ basename($image->path) }}</p>
        <form method="POST" action="{{ route('admin.moment.media.destroy', $image) }}" aria-describedby="delete-media-question-{{ $image->id }}" class="mt-3 flex flex-wrap gap-2">
            @csrf
            @method('DELETE')
            <button type="button" class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200 dark:hover:bg-neutral-700" onclick="const panel = this.closest('details'); panel.open = false; panel.querySelector('summary').focus();">Batal</button>
            <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-transparent bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">Ya, hapus</button>
        </form>
    </div>
</details>
