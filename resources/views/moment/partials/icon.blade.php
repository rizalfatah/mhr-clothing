<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($icon)
        @case('arrow')<path d="M5 12h14m-6-6 6 6-6 6"/>@break
        @case('chevron')<path d="m9 5 7 7-7 7"/>@break
        @case('pin')<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>@break
        @case('whatsapp')<path d="M21 11.5a9 9 0 0 1-13.4 8L3 21l1.5-4.5A9 9 0 1 1 21 11.5Z"/><path d="M8 7.5c-.8 4 2.5 7.4 6.5 8l1-2-2-1-1 1c-1.7-.8-2.7-1.8-3.5-3.5l1-1-1-2Z"/>@break
        @case('instagram')<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/>@break
        @case('camera')<path d="M3 7h4l2-3h6l2 3h4v13H3Z"/><circle cx="12" cy="13" r="4"/>@break
        @case('check')<path d="m5 12 4 4L19 6"/>@break
        @case('expand')<path d="M8 3H3v5m13-5h5v5M3 16v5h5m13-5v5h-5"/>@break
    @endswitch
</svg>
