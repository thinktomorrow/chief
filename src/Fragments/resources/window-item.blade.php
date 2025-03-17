<div data-fragment data-sortable-id="{{ $model->fragmentModel()->id }}" class="w-full">
    <div class="space-y-2 py-4">
        <div class="flex items-start justify-end space-x-3">
            <x-chief-table::button
                data-sortable-handle
                title="herschikken"
                class="shrink-0"
                size="sm"
                variant="outline-white"
            >
                <x-chief::icon.drag-drop-vertical />
            </x-chief-table::button>

            <div class="my-1 flex grow flex-wrap items-start gap-1">
                <span class="h1-dark font-medium leading-6">
                    {{ ucfirst($resource->getLabel()) }}
                </span>

                @if ($model->fragmentModel()->isOffline())
                    <span class="label label-xs label-error">Offline</span>
                @endif

                @if ($model->fragmentModel()->isShared())
                    <span class="label label-xs label-warning">Gedeeld fragment</span>
                @endif

                @if ($model instanceof \Thinktomorrow\Chief\Fragments\Assistants\HasBookmark)
                    <x-chief::copy-button
                        :content="'#'.$model->getBookmark()"
                        successContent="#{{ $model->getBookmark() }} gekopieerd!"
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
            </div>

            @adminCan('fragment-edit')
            <x-chief-table::button
                data-sidebar-trigger
                :href="$manager->route('fragment-edit', $owner, $model)"
                title="Fragment aanpassen"
                class="shrink-0"
                size="sm"
                variant="grey"
            >
                <x-chief::icon.quill-write />
            </x-chief-table::button>
            @endAdminCan
        </div>

        @if ($adminFragment = $model->renderAdminFragment($owner, $loop))
            <div class="px-[2.65rem]">
                {!! $adminFragment !!}
            </div>
        @endif
    </div>

    @include('chief::manager.windows.fragments.component.fragment-select')
</div>
