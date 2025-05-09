@php
    $radioId = 'js-trigger-' . mt_rand(0, 9999);
@endphp

<label for="{{ $radioId }}" class="flex items-start gap-2">
    <x-chief::form.input.radio
        wire:model="sorters.{{ $getKey() }}"
        id="{{ $radioId }}"
        name="sorter"
        value="{{ $getValue() }}"
    />

    <span class="body body-dark leading-5">{!! $getLabel() ?? $getKey() !!}</span>
</label>
