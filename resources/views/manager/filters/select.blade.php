@php
    $label = $label ?? null;
    $description = $description ?? null;
@endphp

<x-chief::input.group :rule="$id">
    @if ($label)
        <x-chief::input.label for="{{ $id }}">{{ $label }}</x-chief::input.label>
    @endif

    @if ($description)
        <x-chief::input.description>{{ $description }}</x-chief::input.description>
    @endif

    <chief-multiselect
        id="{{ $id }}"
        name="{{ $name }}"
        :options='@json($options)'
        selected='@json($value ?: $default)'
        :multiple='@json($multiple)'
        @if($isGrouped)
            grouplabel="group"
            groupvalues="values"
            labelkey="label"
            valuekey="id"
        @endif
    />
</x-chief::input.group>
