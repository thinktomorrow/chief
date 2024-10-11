@php
    $dropdownId = 'table-hidden-row-actions-dropdown-' . $item->getId();
    $visibleRowActions = $this->getVisibleRowActions($item);
    $hiddenRowActions = $this->getHiddenRowActions($item);
@endphp

<div class="flex min-h-6 items-center justify-end gap-1">
    @foreach ($visibleRowActions as $action)
        <x-chief-table::action.button :action="$action" :item="$item" size="xs" variant="secondary" />
    @endforeach

    @if (count($hiddenRowActions) > 0)
        <button type="button" x-on:click="$dispatch('open-dialog', { 'id': '{{ $dropdownId }}' })">
            <x-chief-table::button size="xs" variant="quarternary">
                <x-chief::icon.more-vertical-circle />
            </x-chief-table::button>
        </button>

        <x-chief::dialog.dropdown id="{{ $dropdownId }}" placement="bottom-end">
            @foreach ($hiddenRowActions as $action)
                <x-chief-table::action.dropdown.item :action="$action" :item="$item" />
            @endforeach
        </x-chief::dialog.dropdown>
    @endif
</div>
