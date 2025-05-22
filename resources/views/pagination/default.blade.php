@if ($paginator->hasPages())
    <nav
        role="navigation"
        aria-label="{{ __('Pagination Navigation') }}"
        class="mt-4 flex items-center justify-between"
    >
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span
                    class="border-grey-200 text-grey-500 relative inline-flex cursor-default items-center rounded-md border bg-white px-4 py-2 text-sm leading-5 font-medium"
                >
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    class="border-grey-200 text-grey-700 ring-grey-300 hover:text-grey-500 active:bg-grey-100 active:text-grey-700 relative inline-flex items-center rounded-md border bg-white px-4 py-2 text-sm leading-5 font-medium transition duration-150 ease-in-out focus:border-blue-300 focus:ring-3 focus:outline-hidden"
                >
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    class="border-grey-200 text-grey-700 ring-grey-300 hover:text-grey-500 active:bg-grey-100 active:text-grey-700 relative ml-3 inline-flex items-center rounded-md border bg-white px-4 py-2 text-sm leading-5 font-medium transition duration-150 ease-in-out focus:border-blue-300 focus:ring-3 focus:outline-hidden"
                >
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span
                    class="border-grey-200 text-grey-500 relative ml-3 inline-flex cursor-default items-center rounded-md border bg-white px-4 py-2 text-sm leading-5 font-medium"
                >
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden gap-4 sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div class="shrink-0">
                <p class="text-grey-700 text-sm leading-5">
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    -
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    van
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    resultaten
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-md shadow-sm">
                    {{-- Previous Page Link --}}

                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span
                                class="border-grey-200 text-grey-500 relative inline-flex cursor-default items-center rounded-l-md border bg-white px-2 py-2 text-sm leading-5 font-medium"
                                aria-hidden="true"
                            >
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a
                            href="{{ $paginator->previousPageUrl() }}"
                            rel="prev"
                            class="border-grey-200 text-grey-500 ring-grey-300 hover:text-grey-400 active:bg-grey-100 active:text-grey-500 relative inline-flex items-center rounded-l-md border bg-white px-2 py-2 text-sm leading-5 font-medium transition duration-150 ease-in-out focus:z-10 focus:border-blue-300 focus:ring-3 focus:outline-hidden"
                            aria-label="{{ __('pagination.previous') }}"
                        >
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span
                                aria-disabled="true"
                                class="border-grey-200 text-grey-700 relative -ml-px inline-flex cursor-default items-center border bg-white px-4 py-2 text-sm leading-5 font-medium"
                            >
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span
                                        aria-current="page"
                                        class="border-grey-200 text-grey-500 relative -ml-px inline-flex cursor-default items-center border bg-white px-4 py-2 text-sm leading-5 font-medium"
                                    >
                                        {{ $page }}
                                    </span>
                                @else
                                    <a
                                        href="{{ $url }}"
                                        class="border-grey-200 text-grey-700 ring-grey-300 hover:text-grey-500 active:bg-grey-100 active:text-grey-700 relative -ml-px inline-flex items-center border bg-white px-4 py-2 text-sm leading-5 font-medium transition duration-150 ease-in-out focus:z-10 focus:border-blue-300 focus:ring-3 focus:outline-hidden"
                                        aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                    >
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}

                    @if ($paginator->hasMorePages())
                        <a
                            href="{{ $paginator->nextPageUrl() }}"
                            rel="next"
                            class="border-grey-200 text-grey-500 ring-grey-300 hover:text-grey-400 active:bg-grey-100 active:text-grey-500 relative -ml-px inline-flex items-center rounded-r-md border bg-white px-2 py-2 text-sm leading-5 font-medium transition duration-150 ease-in-out focus:z-10 focus:border-blue-300 focus:ring-3 focus:outline-hidden"
                            aria-label="{{ __('pagination.next') }}"
                        >
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </a>
                    @else
                        <span
                            aria-disabled="true"
                            aria-label="{{ __('pagination.next') }}"
                            class="border-grey-200 text-grey-500 relative -ml-px inline-flex cursor-default items-center rounded-r-md border bg-white px-2 py-2 text-sm leading-5 font-medium"
                            aria-hidden="true"
                        >
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
