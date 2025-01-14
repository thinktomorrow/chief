@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="mt-4 flex justify-between">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span
                class="text-gray-500 border-gray-300 relative inline-flex cursor-default items-center rounded-md border bg-white px-4 py-2 text-sm font-medium leading-5"
            >
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a
                href="{{ $paginator->previousPageUrl() }}"
                rel="prev"
                class="text-gray-700 border-gray-300 hover:text-gray-500 ring-gray-300 active:bg-gray-100 active:text-gray-700 relative inline-flex items-center rounded-md border bg-white px-4 py-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:border-blue-300 focus:outline-none focus:ring"
            >
                {!! __('pagination.previous') !!}
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a
                href="{{ $paginator->nextPageUrl() }}"
                rel="next"
                class="text-gray-700 border-gray-300 hover:text-gray-500 ring-gray-300 active:bg-gray-100 active:text-gray-700 relative inline-flex items-center rounded-md border bg-white px-4 py-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:border-blue-300 focus:outline-none focus:ring"
            >
                {!! __('pagination.next') !!}
            </a>
        @else
            <span
                class="text-gray-500 border-gray-300 relative inline-flex cursor-default items-center rounded-md border bg-white px-4 py-2 text-sm font-medium leading-5"
            >
                {!! __('pagination.next') !!}
            </span>
        @endif
    </nav>
@endif
