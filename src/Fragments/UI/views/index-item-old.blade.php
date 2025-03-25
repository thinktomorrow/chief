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
                        <x-chief::link
                            x-copy="{ content: '#{{ $fragment->getBookmark() }}', successContent: '#{{ $fragment->getBookmark() }} gekopieerd!' }"
                            class="my-0.5 leading-5 !text-grey-400 hover:!text-primary-500"
                        >
                            <x-chief::icon.link />
                        </x-chief::link>
                    @endif
                </span>
            </div>

            <x-chief::button
                data-sidebar-trigger
                href="{{ route('chief::fragments.edit', [$context->id, $fragment->getFragmentId()]) }}"
                title="Fragment aanpassen"
                class="shrink-0"
            >
                <x-chief::icon.quill-write />
            </x-chief::button>
        </div>

        @if ($adminFragment = $fragment->renderInAdmin())
            <div class="px-[2.65rem]">
                {!! $adminFragment !!}
            </div>
        @endif
    </div>

    @include('chief-fragments::components.fragment-select')
</div>
