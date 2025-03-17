<div
    wire:key="{{ 'context-fragment-' . $fragment->getId() }}"
    x-sortable-item="{{ $fragment->fragmentId }}"
    class="w-full"
>
    <div class="space-y-4 py-4">
        <div class="flex items-start justify-end gap-3">
            <x-chief-table::button
                x-sortable-handle
                size="sm"
                variant="outline-white"
                title="herschikken"
                class="shrink-0"
            >
                <x-chief::icon.drag-drop-vertical />
            </x-chief-table::button>

            <div class="my-1 flex grow flex-wrap items-start gap-1">
                <span class="h1-dark font-medium leading-6">
                    {{ ucfirst($fragment->label) }} - order: {{ $fragment->order }}
                </span>

                <span class="with-xs-labels align-bottom">
                    @if (! $fragment->isOnline)
                        <span class="label label-error">Offline</span>
                    @endif

                    @if ($fragment->isShared)
                        <span class="label label-xs label-warning">Gedeeld fragment</span>
                    @endif

                    @if ($fragment->bookmark)
                        <x-chief::copy-button
                            :content="'#'.$fragment->bookmark"
                            successContent="#{{ $fragment->bookmark }} gekopieerd!"
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

            <x-chief-table::button
                x-on:click="$wire.editFragment('{{ $fragment->fragmentId }}')"
                size="sm"
                variant="grey"
                title="Fragment aanpassen"
                class="shrink-0"
            >
                <x-chief::icon.quill-write />
            </x-chief-table::button>
        </div>

        @if ($adminFragment = $fragment->content)
            <div class="px-[2.65rem]">
                {!! $adminFragment !!}
            </div>
        @endif
    </div>

    @include(
        'chief-fragments::livewire._partials.add-fragment-button',
        [
            'order' => $fragment->order,
            'parentId' => $parentId,
        ]
    )
</div>
