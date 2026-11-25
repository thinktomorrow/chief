@php
    $id = 'toggle-filter-' . $this->getId() . '-' . $getKey();
@endphp

<label for="{{ $id }}" class="text-grey-800 flex cursor-pointer items-start gap-2 py-2 leading-5">
    <x-chief::form.input.checkbox
        wire:model.live="filters.{{ $getKey() }}"
        id="{{ $id }}"
        value="1"
        :checked="$isOn($this->filters[$getKey()] ?? null)"
        class="hidden appearance-none"
    />

    <span class="form-input-toggle form-input-toggle-sm shrink-0"></span>

    <span class="text-nowrap">{!! $getSwitchLabel() !!}</span>
</label>
