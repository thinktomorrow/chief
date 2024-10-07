@php
    $dropdownId = 'table-hidden-row-actions';
@endphp

<div class="flex min-h-6 items-center justify-end gap-1.5">

@foreach ($this->getVisibleRowActions() as $action)
        <a href="#" title="...">
            <x-chief-table::button color="white" size="xs">
                <x-chief::icon.quill-write />
            </x-chief-table::button>
        </a>

    {{ $action }}
@endforeach
</div>

@if (count($this->getHiddenRowActions()) > 0)
    <button type="button" x-on:click="$dispatch('open-dialog', { 'id': '{{ $dropdownId }}' })">
        <x-chief-table::button color="white">
            <x-chief::icon.more-vertical-circle />
        </x-chief-table::button>
    </button>

    <x-chief::dialog.dropdown id="{{ $dropdownId }}" placement="bottom-end">
        @foreach ($this->getHiddenRowActions() as $action)
            {{ $action }}
        @endforeach
    </x-chief::dialog.dropdown>
@endif
