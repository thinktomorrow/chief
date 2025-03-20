<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="flex flex-1 justify-between sm:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <x-chief::button size="sm" variant="outline-white" class="pointer-events-none text-grey-400">
                            <x-chief::icon.chevron-left />
                            <span>Vorige</span>
                        </x-chief::button>
                    @else
                        <x-chief::button
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            wire:loading.attr="disabled"
                            dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                            size="sm"
                            variant="outline-white"
                        >
                            <x-chief::icon.chevron-left />
                            <span>Vorige</span>
                        </x-chief::button>
                    @endif
                </span>

                <span>
                    @if ($paginator->hasMorePages())
                        <x-chief::button
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            wire:loading.attr="disabled"
                            dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                            size="sm"
                            variant="outline-white"
                        >
                            <span>Vorige</span>
                            <x-chief::icon.chevron-right />
                        </x-chief::button>
                    @else
                        <x-chief::button size="sm" variant="outline-white" class="pointer-events-none text-grey-400">
                            <span>Vorige</span>
                            <x-chief::icon.chevron-right />
                        </x-chief::button>
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
                    <span class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm">
                        <span>
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                    <x-chief::button
                                        size="sm"
                                        variant="outline-white"
                                        class="rounded-r-none text-grey-400"
                                    >
                                        <x-chief::icon.chevron-left />
                                    </x-chief::button>
                                </span>
                            @else
                                <x-chief::button
                                    wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                    dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="prev"
                                    aria-label="{{ __('pagination.previous') }}"
                                    size="sm"
                                    variant="outline-white"
                                    class="rounded-r-none hover:relative"
                                >
                                    <x-chief::icon.chevron-left />
                                </x-chief::button>
                            @endif
                        </span>

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <x-chief::button
                                        size="sm"
                                        variant="outline-white"
                                        class="pointer-events-none min-w-[1.875rem] justify-center rounded-l-none rounded-r-none font-normal text-grey-400 hover:relative"
                                    >
                                        {{ $element }}
                                    </x-chief::button>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <x-chief::button
                                                    size="sm"
                                                    variant="outline-white"
                                                    class="pointer-events-none min-w-[1.875rem] justify-center rounded-l-none rounded-r-none font-normal text-grey-400 hover:relative"
                                                >
                                                    {{ $page }}
                                                </x-chief::button>
                                            </span>
                                        @else
                                            <x-chief::button
                                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                                aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                                size="sm"
                                                variant="outline-white"
                                                class="min-w-[1.875rem] justify-center rounded-l-none rounded-r-none font-normal hover:relative"
                                            >
                                                {{ $page }}
                                            </x-chief::button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        <span>
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <x-chief::button
                                    wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                    dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="next"
                                    aria-label="{{ __('pagination.next') }}"
                                    size="sm"
                                    variant="outline-white"
                                    class="rounded-l-none hover:relative"
                                >
                                    <x-chief::icon.chevron-right />
                                </x-chief::button>
                            @else
                                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                    <x-chief::button
                                        size="sm"
                                        variant="outline-white"
                                        class="rounded-l-none text-grey-400"
                                    >
                                        <x-chief::icon.chevron-right />
                                    </x-chief::button>
                                </span>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
