@php
    $label = $label ?? null;
    $description = $description ?? null;
@endphp

<x-chief::input.group :rule="$id">
    @if ($label)
        <x-chief::input.label for="{{ $id }}" unset class="font-medium h6 body-dark">{{ $label }}</x-chief::input.label>
    @endif

    @if ($description)
        <x-chief::input.description>{{ $description }}</x-chief::input.description>
    @endif

    <x-chief::multiselect
        id="{{ $id }}"
        name="{{ $name . ($multiple ? '[]' : '') }}"
        :options='$options'
        :selection='$value ?: $default'
        :multiple='$multiple'
    />
</x-chief::input.group>
