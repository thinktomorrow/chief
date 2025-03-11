@php
    $label = $label ?? null;
    $description = $description ?? null;
@endphp

<x-chief::input.group :rule="$id">
    @if ($label)
        <x-chief::form.label for="{{ $id }}" unset class="h6 body-dark font-medium">
            {{ $label }}
        </x-chief::form.label>
    @endif

    @if ($description)
        <x-chief::form.description>{{ $description }}</x-chief::form.description>
    @endif

    <x-chief::multiselect
        id="{{ $id }}"
        name="{{ $name . ($multiple ? '[]' : '') }}"
        :options='$options'
        :selection='$value ?: $default'
        :multiple='$multiple'
    />
</x-chief::input.group>
