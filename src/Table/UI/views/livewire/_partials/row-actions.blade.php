@php
    $dropdownId = 'table-hidden-row-actions';

    $visibleRowActions = $this->getVisibleRowActions($item);
    $hiddenRowActions = $this->getHiddenRowActions($item);
@endphp

<div class="flex min-h-6 items-center justify-end gap-1.5">
    @foreach ($visibleRowActions as $action)
        <x-chief-table::action.button :action="$action" size="xs" color="white" />
    @endforeach
</div>

@if (count($hiddenRowActions) > 0)
    <button type="button" x-on:click="$dispatch('open-dialog', { 'id': '{{ $dropdownId }}' })">
        <x-chief-table::button color="white" size="xs">
            <x-chief::icon.more-vertical-circle />
        </x-chief-table::button>
    </button>

    <x-chief::dialog.dropdown id="{{ $dropdownId }}" placement="bottom-end">
        @foreach ($hiddenRowActions as $action)
            {{ $action }}
        @endforeach
    </x-chief::dialog.dropdown>
@endif
