<div data-fragment data-sortable-id="{{ $model->fragmentModel()->id }}" class="w-full">
    <div class="py-6 space-y-4">
        <div class="flex items-start justify-end space-x-3">
            <button type="button" data-sortable-handle class="shrink-0">
                <x-chief::button>
                    <svg><use xlink:href="#icon-chevron-up-down"></use></svg>
                </x-chief::button>
            </button>

            <div class="flex flex-wrap items-start gap-1 my-1 grow">
                <span class="font-medium leading-6 h1-dark">
                    {{ ucfirst($resource->getLabel()) }}
                </span>

                @if($model->fragmentModel()->isOffline())
                    <span class="label label-xs label-error"> Offline </span>
                @endif

                @if($model->fragmentModel()->isShared())
                    <span class="label label-xs label-warning"> Gedeeld fragment </span>
                @endif

                @if($model instanceof \Thinktomorrow\Chief\Fragments\Assistants\HasBookmark)
                    <x-chief::copy-button
                        :content="'#'.$model->getBookmark()"
                        successContent="#{{ $model->getBookmark() }} gekopieerd!"
                        class="my-0.5 leading-5"
                    >
                        <x-chief::link class="!text-grey-400 hover:!text-primary-500">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
                            </svg>
                        </x-chief::link>
                    </x-chief::copy-button>
                @endif
            </div>

            @adminCan('fragment-edit')
                <a
                    data-sidebar-trigger
                    href="@adminRoute('fragment-edit', $owner, $model)"
                    title="Fragment aanpassen"
                    class="shrink-0"
                >
                    <x-chief::button>
                        <svg><use xlink:href="#icon-edit"></use></svg>
                    </x-chief::button>
                </a>
            @endAdminCan
        </div>

        @if($adminFragment = $model->renderAdminFragment($owner, $loop))
            <div class="px-[2.65rem]">
                {!! $adminFragment !!}
            </div>
        @endif
    </div>

    @include('chief::manager.windows.fragments.component.fragment-select')
</div>
