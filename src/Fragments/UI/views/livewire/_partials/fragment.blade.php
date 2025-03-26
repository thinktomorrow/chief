<div
    data-slot="fragment"
    wire:key="{{ 'context-fragment-' . $fragment->getId() }}"
    x-sortable-item="{{ $fragment->fragmentId }}"
>
    <div class="space-y-2 py-4">
        <div class="flex items-start justify-end gap-3">
            <x-chief::button x-sortable-handle size="sm" variant="outline-white" title="herschikken" class="shrink-0">
                <x-chief::icon.drag-drop-vertical />
            </x-chief::button>

            <div class="mt-[0.1875rem] flex grow flex-wrap items-start gap-2">
                <h3 class="text-base/6 font-medium text-grey-800">
                    {{ ucfirst($fragment->label) }}
                </h3>

                <div class="flex flex-wrap items-start gap-1">
                    @if (! $fragment->isOnline)
                        <x-chief::badge size="sm" variant="grey" class="block">Offline</x-chief::badge>
                    @endif

                    @if ($fragment->isShared)
                        <x-chief::badge size="sm" variant="blue" class="block">Gedeeld fragment</x-chief::badge>
                    @endif
                </div>
            </div>

            <x-chief::button
                x-on:click="$wire.editFragment('{{ $fragment->fragmentId }}')"
                size="sm"
                variant="grey"
                title="Fragment aanpassen"
                class="shrink-0"
            >
                <x-chief::icon.quill-write />
            </x-chief::button>
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
