@php
    $dropdownId = 'table-hidden-row-actions-' . $this->getRowKey($item);
    $primaryRowActions = $this->getPrimaryRowActions($item);
    $secondaryRowActions = $this->getSecondaryRowActions($item);
    $tertiaryRowActions = $this->getTertiaryRowActions($item);
@endphp

<div class="flex min-h-6 items-center justify-end gap-1">
    @foreach ($secondaryRowActions as $action)
        <x-chief-table::action.button
            :action="$action"
            wire:click="applyRowAction('{{ $action->getKey() }}', '{{ $item->modelReference()->getShort() }}')"
            size="xs"
            variant="outline-white"
        />
    @endforeach

    @foreach ($primaryRowActions as $action)
        <x-chief-table::action.button
            :action="$action"
            wire:click="applyRowAction('{{ $action->getKey() }}', '{{ $item->modelReference()->getShort() }}')"
            size="xs"
            variant="grey"
        />
    @endforeach

    @if (count($tertiaryRowActions) > 0)
        <x-chief::button
            size="xs"
            variant="transparent"
            x-on:click="$dispatch('open-dialog', { 'id': '{{ $dropdownId }}' })"
        >
            <x-chief::icon.more-vertical-circle />
        </x-chief::button>

        <template x-teleport="body">
            <x-chief::dialog.dropdown id="{{ $dropdownId }}" placement="bottom-end">
                @foreach ($tertiaryRowActions as $action)
                    <x-chief-table::action.dropdown.item
                        wire:click="applyRowAction('{{ $action->getKey() }}', '{{ $item->modelReference()->getShort() }}')"
                        :action="$action"
                        variant="grey"
                    />
                @endforeach
            </x-chief::dialog.dropdown>
        </template>
    @endif
</div>
