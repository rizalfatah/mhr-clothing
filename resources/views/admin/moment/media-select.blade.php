@php($selectedMedia = $media->firstWhere('path', $value))
<div class="space-y-2" data-media-picker data-media-label="{{ $label }}">
    <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">{{ $label }}</p>
    <input type="hidden" name="{{ $field }}" value="{{ $value }}" data-media-value>
    <button type="button" data-media-open aria-haspopup="dialog" aria-controls="moment-media-library" aria-label="Pilih foto: {{ $label }}" class="w-full overflow-hidden rounded-lg border border-gray-200 bg-gray-50 text-left hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-900">
        <div data-media-preview @if(!$selectedMedia) hidden @endif>
            <img @if($selectedMedia) src="{{ \App\Services\MomentContent::imageUrl($selectedMedia->path) }}" @endif alt="{{ $selectedMedia?->alt_text ?? '' }}" class="{{ $field === 'gallery[]' ? 'h-32' : 'h-48' }} w-full object-contain" loading="lazy" data-media-preview-image>
        </div>
        <div class="flex min-h-20 flex-col items-center justify-center gap-1 p-4 text-gray-500 dark:text-neutral-400" data-media-empty @if($selectedMedia) hidden @endif>
            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8" cy="8" r="1.5"/><path d="m3 17 6-6 4 4 3-3 5 5"/></svg>
            <span class="text-xs">Belum ada foto dipilih</span>
        </div>
        <span class="flex items-center justify-between gap-3 border-t border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-neutral-700 dark:bg-neutral-800">
            <span class="min-w-0 truncate text-gray-600 dark:text-neutral-300" data-media-caption>{{ $selectedMedia?->alt_text ?? 'Buka galeri foto' }}</span>
            <span class="shrink-0 font-medium text-blue-600 dark:text-blue-400">Pilih foto</span>
        </span>
    </button>
</div>
