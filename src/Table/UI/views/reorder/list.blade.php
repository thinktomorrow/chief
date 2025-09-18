@php
    $results = $this->getReorderResults();
    $sortableGroup = 'reorder-table-item';
    $variant ??= 'transparent';
@endphp

<div class="space-y-4">
    <div class="flex items-start justify-between gap-4">
        <div class="prose prose-dark prose-spacing mt-1.5">
            <p>Pas de volgorde aan door naar de gewenste plek te slepen.</p>
        </div>

        <x-chief::button class="shrink-0" size="base" variant="grey" wire:click="stopReordering()">
            <x-chief::icon.arrow-turn-backward />
            <span>Stop met herschikken</span>
        </x-chief::button>
    </div>

    @include('chief-table::livewire._partials.table-container-header')

    <div
        x-sortable
        x-sortable-group="{{ $sortableGroup }}"
        x-sortable-ghost-class="table-sort-ghost"
        x-sortable-drag-class="table-sort-drag"
        x-data="{
        handleEnd(evt) {
            // reorder within same parent
            if (evt.to === evt.from) {
                $wire.reorder(evt.target.sortable.toArray())
                return
            }

            // reorder to different parent
            const itemId = evt.item.getAttribute('x-sortable-item')
            const parentId =
                evt.to.closest('[x-sortable-item]')?.getAttribute('x-sortable-item') || null
            const ids = [...evt.to.children].map((el) =>
                el.getAttribute('x-sortable-item'),
            )

            $wire.moveToParent(itemId, parentId, ids)
        }
    }"
        x-on:end.stop="handleEnd($event)"
        @class([
            'border-grey-100 shadow-grey-500/10 divide-grey-100 divide-y rounded-xl border px-1 py-1.5',
            'bg-white shadow-md' => $variant === 'card',
            '' => $variant === 'transparent',
        ])
    >
        @foreach ($results as $itemIndex => $item)
            @include(
                'chief-table::reorder.list-item',
                [
                    'item' => $item,
                    'sortableGroup' => $sortableGroup,
                    'indent' => 0,
                ]
            )
        @endforeach
    </div>

    @include('chief-table::livewire._partials.table-container-footer')
</div>
