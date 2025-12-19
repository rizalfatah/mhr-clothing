@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="w-full">
        <div class="flex flex-col items-center gap-4">
            {{-- First Row: Navigation Buttons --}}
            <div class="flex items-center justify-center gap-1">
                {{-- Previous Button --}}
                @if ($paginator->onFirstPage())
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-primary-300 bg-primary-50 border border-primary-200 cursor-default rounded-full dark:text-primary-500 dark:bg-primary-900 dark:border-primary-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}"
                        class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-primary-700 bg-white border border-primary-200 rounded-full hover:bg-primary-50 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 active:bg-primary-100 transition ease-in-out duration-150 dark:bg-primary-800 dark:border-primary-700 dark:text-primary-100 dark:hover:bg-primary-700"
                        aria-label="Previous page">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                @endif

                {{-- Page Numbers --}}
                <div class="flex items-center gap-1 mx-2">
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span
                                class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-primary-400 dark:text-primary-500">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page"
                                        class="inline-flex items-center justify-center w-10 h-10 text-sm font-semibold text-white bg-primary-600 rounded-full shadow-sm dark:bg-primary-500">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-primary-700 bg-white border border-primary-200 rounded-full hover:bg-primary-50 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:z-10 active:bg-primary-100 transition ease-in-out duration-150 dark:bg-primary-800 dark:border-primary-700 dark:text-primary-200 dark:hover:bg-primary-700 dark:hover:text-white"
                                        aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>

                {{-- Next Button --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}"
                        class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-primary-700 bg-white border border-primary-200 rounded-full hover:bg-primary-50 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 active:bg-primary-100 transition ease-in-out duration-150 dark:bg-primary-800 dark:border-primary-700 dark:text-primary-100 dark:hover:bg-primary-700"
                        aria-label="Next page">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-primary-300 bg-primary-50 border border-primary-200 cursor-default rounded-full dark:text-primary-500 dark:bg-primary-900 dark:border-primary-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </div>

            {{-- Second Row: Results Text --}}
            <div class="text-sm text-primary-700 dark:text-primary-300 text-center">
                Showing
                @if ($paginator->firstItem())
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    -
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                of
                <span class="font-medium">{{ $paginator->total() }}</span>
                results
            </div>
        </div>
    </nav>
@endif
