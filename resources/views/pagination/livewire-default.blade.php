<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="flex flex-1 justify-between sm:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <x-chief-table-new::button
                            size="sm"
                            color="white"
                            iconLeft='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M15 6C15 6 9.00001 10.4189 9 12C8.99999 13.5812 15 18 15 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
                            class="pointer-events-none text-grey-400"
                        >
                            Vorige
                        </x-chief-table-new::button>
                    @else
                        <button
                            type="button"
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            wire:loading.attr="disabled"
                            dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                        >
                            <x-chief-table-new::button
                                size="sm"
                                color="white"
                                iconLeft='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M15 6C15 6 9.00001 10.4189 9 12C8.99999 13.5812 15 18 15 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
                            >
                                Vorige
                            </x-chief-table-new::button>
                        </button>
                    @endif
                </span>

                <span>
                    @if ($paginator->hasMorePages())
                        <button
                            type="button"
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            wire:loading.attr="disabled"
                            dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                        >
                            <x-chief-table-new::button
                                size="sm"
                                color="white"
                                iconRight='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M9.00005 6C9.00005 6 15 10.4189 15 12C15 13.5812 9 18 9 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
                            >
                                Volgende
                            </x-chief-table-new::button>
                        </button>
                    @else
                        <x-chief-table-new::button
                            size="sm"
                            color="white"
                            iconRight='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M9.00005 6C9.00005 6 15 10.4189 15 12C15 13.5812 9 18 9 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
                            class="pointer-events-none text-grey-400"
                        >
                            Volgende
                        </x-chief-table-new::button>
                    @endif
                </span>
            </div>

            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm leading-5 text-grey-500">
                        <span>{{ $paginator->firstItem() }}</span>
                        <span>-</span>
                        <span>{{ $paginator->lastItem() }}</span>
                        <span>van</span>
                        <span>{{ $paginator->total() }}</span>
                        <span>resultaten</span>
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex rounded-md shadow-sm">
                        <span>
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                    <x-chief-table-new::button
                                        size="sm"
                                        color="white"
                                        iconLeft='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M15 6C15 6 9.00001 10.4189 9 12C8.99999 13.5812 15 18 15 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
                                        class="rounded-r-none text-grey-400"
                                    />
                                </span>
                            @else
                                <button
                                    type="button"
                                    wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                    dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="prev"
                                    aria-label="{{ __('pagination.previous') }}"
                                >
                                    <x-chief-table-new::button
                                        size="sm"
                                        color="white"
                                        iconLeft='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M15 6C15 6 9.00001 10.4189 9 12C8.99999 13.5812 15 18 15 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
                                        class="rounded-r-none hover:relative"
                                    />
                                </button>
                            @endif
                        </span>

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <x-chief-table-new::button
                                        size="sm"
                                        color="white"
                                        class="pointer-events-none min-w-[1.875rem] rounded-l-none rounded-r-none font-normal text-grey-400 hover:relative"
                                    >
                                        {{ $element }}
                                    </x-chief-table-new::button>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <x-chief-table-new::button
                                                    size="sm"
                                                    color="white"
                                                    class="pointer-events-none min-w-[1.875rem] rounded-l-none rounded-r-none font-normal text-grey-400 hover:relative"
                                                >
                                                    {{ $page }}
                                                </x-chief-table-new::button>
                                            </span>
                                        @else
                                            <button
                                                type="button"
                                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                                aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                            >
                                                <x-chief-table-new::button
                                                    size="sm"
                                                    color="white"
                                                    class="min-w-[1.875rem] rounded-l-none rounded-r-none font-normal hover:relative"
                                                >
                                                    {{ $page }}
                                                </x-chief-table-new::button>
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        <span>
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <button
                                    type="button"
                                    wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                    dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="next"
                                    aria-label="{{ __('pagination.next') }}"
                                >
                                    <x-chief-table-new::button
                                        size="sm"
                                        color="white"
                                        iconLeft='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M9.00005 6C9.00005 6 15 10.4189 15 12C15 13.5812 9 18 9 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
                                        class="rounded-l-none hover:relative"
                                    />
                                </button>
                            @else
                                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                    <x-chief-table-new::button
                                        size="sm"
                                        color="white"
                                        iconLeft='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M9.00005 6C9.00005 6 15 10.4189 15 12C15 13.5812 9 18 9 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
                                        class="rounded-l-none text-grey-400"
                                    />
                                </span>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
