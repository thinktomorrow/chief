<div data-fragment data-sortable-id="{{ $fragment->getFragmentId() }}" class="w-full">
    <div class="space-y-4 py-6">
        <div class="flex items-start justify-end space-x-3">
            <button type="button" data-sortable-handle class="shrink-0">
                <x-chief::button>
                    <svg>
                        <use xlink:href="#icon-chevron-up-down"></use>
                    </svg>
                </x-chief::button>
            </button>

            <x-chief::button variant="grey" size="sm">
                <x-chief::icon.trash />
                <span>Verwijder</span>
            </x-chief::button>

            <div class="my-1 flex grow flex-wrap items-start gap-1">
                <span class="h1-dark font-medium leading-6">
                    {{ ucfirst($resource->getLabel()) }}
                </span>

                <span class="with-xs-labels align-bottom">
                    @if ($fragment->fragmentModel()->isOffline())
                        <span class="label label-error">Offline</span>
                    @endif

                    @if ($fragment->fragmentModel()->isShared())
                        <span class="label label-xs label-warning">Gedeeld fragment</span>
                    @endif

                    @if ($fragment instanceof \Thinktomorrow\Chief\Fragments\Assistants\HasBookmark)
                        <x-chief::copy-button
                            :content="'#'.$fragment->getBookmark()"
                            successContent="#{{ $fragment->getBookmark() }} gekopieerd!"
                            class="my-0.5 leading-5"
                        >
                            <x-chief::link class="!text-grey-400 hover:!text-primary-500">
                                <svg
                                    class="h-5 w-5"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5"
                                    />
                                </svg>
                            </x-chief::link>
                        </x-chief::copy-button>
                    @endif
                </span>
            </div>
            <a
                data-sidebar-trigger
                href="{{ route('chief::fragments.edit', [$context->id, $fragment->getFragmentId()]) }}"
                title="Fragment aanpassen"
                class="shrink-0"
            >
                <x-chief::icon-button icon="icon-edit" />
            </a>
        </div>

        @if ($adminFragment = $fragment->renderInAdmin())
            <div class="px-[2.65rem]">
                {!! $adminFragment !!}
            </div>
        @endif
    </div>

    @include('chief-fragments::components.fragment-select')
</div>
