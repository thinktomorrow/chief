@if ($paginator->hasPages())
    <nav
        role="navigation"
        aria-label="{{ __('Pagination Navigation') }}"
        class="mt-4 flex items-center justify-between"
    >
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span
                    class="relative inline-flex cursor-default items-center rounded-md border border-grey-200 bg-white px-4 py-2 text-sm font-medium leading-5 text-grey-500"
                >
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    class="relative inline-flex items-center rounded-md border border-grey-200 bg-white px-4 py-2 text-sm font-medium leading-5 text-grey-700 ring-grey-300 transition duration-150 ease-in-out hover:text-grey-500 focus:border-blue-300 focus:outline-none focus:ring active:bg-grey-100 active:text-grey-700"
                >
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    class="relative ml-3 inline-flex items-center rounded-md border border-grey-200 bg-white px-4 py-2 text-sm font-medium leading-5 text-grey-700 ring-grey-300 transition duration-150 ease-in-out hover:text-grey-500 focus:border-blue-300 focus:outline-none focus:ring active:bg-grey-100 active:text-grey-700"
                >
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span
                    class="relative ml-3 inline-flex cursor-default items-center rounded-md border border-grey-200 bg-white px-4 py-2 text-sm font-medium leading-5 text-grey-500"
                >
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden gap-4 sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div class="shrink-0">
                <p class="text-sm leading-5 text-grey-700">
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    -
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    van
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    resultaten
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-md shadow-card">
                    {{-- Previous Page Link --}}

                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span
                                class="relative inline-flex cursor-default items-center rounded-l-md border border-grey-200 bg-white px-2 py-2 text-sm font-medium leading-5 text-grey-500"
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
                            class="relative inline-flex items-center rounded-l-md border border-grey-200 bg-white px-2 py-2 text-sm font-medium leading-5 text-grey-500 ring-grey-300 transition duration-150 ease-in-out hover:text-grey-400 focus:z-10 focus:border-blue-300 focus:outline-none focus:ring active:bg-grey-100 active:text-grey-500"
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
                                class="relative -ml-px inline-flex cursor-default items-center border border-grey-200 bg-white px-4 py-2 text-sm font-medium leading-5 text-grey-700"
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
                                        class="relative -ml-px inline-flex cursor-default items-center border border-grey-200 bg-white px-4 py-2 text-sm font-medium leading-5 text-grey-500"
                                    >
                                        {{ $page }}
                                    </span>
                                @else
                                    <a
                                        href="{{ $url }}"
                                        class="relative -ml-px inline-flex items-center border border-grey-200 bg-white px-4 py-2 text-sm font-medium leading-5 text-grey-700 ring-grey-300 transition duration-150 ease-in-out hover:text-grey-500 focus:z-10 focus:border-blue-300 focus:outline-none focus:ring active:bg-grey-100 active:text-grey-700"
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
                            class="relative -ml-px inline-flex items-center rounded-r-md border border-grey-200 bg-white px-2 py-2 text-sm font-medium leading-5 text-grey-500 ring-grey-300 transition duration-150 ease-in-out hover:text-grey-400 focus:z-10 focus:border-blue-300 focus:outline-none focus:ring active:bg-grey-100 active:text-grey-500"
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
                            class="relative -ml-px inline-flex cursor-default items-center rounded-r-md border border-grey-200 bg-white px-2 py-2 text-sm font-medium leading-5 text-grey-500"
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
