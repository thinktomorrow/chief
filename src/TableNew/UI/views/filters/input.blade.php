{{--@php--}}
{{--    $label = $label ?? null;--}}
{{--    $description = $description ?? null;--}}
{{--@endphp--}}

@props([
    'label' => null,
    'description' => null,
])

<x-chief::input.group :rule="$id">
{{--    @if ($label)--}}
{{--        <x-chief::input.label for="{{ $id }}" unset class="font-medium h6 body-dark">{{ $label }}</x-chief::input.label>--}}
{{--    @endif--}}

{{--    @if ($description)--}}
{{--        <x-chief::input.description>{{ $description }}</x-chief::input.description>--}}
{{--    @endif--}}

    <x-chief::input.text
        wire:model.live="filters.{{ $name }}"
        id="{{ $id }}"
{{--        name="{{ $name }}"--}}
{{--        placeholder="{{ $placeholder }}"--}}
{{--        value="{{ $value ?: $default }}"--}}
    />

{{--        <x-chief::input.text--}}
{{--            wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"--}}
{{--            id="{{ $getElementId($locale ?? null) }}"--}}
{{--            name="{{ $getName($locale ?? null) }}"--}}
{{--            placeholder="{{ $getPlaceholder($locale ?? null) }}"--}}
{{--            value="{{ $getActiveValue($locale ?? null) }}"--}}
{{--            :autofocus="$hasAutofocus()"--}}
{{--            :attributes="$attributes->merge($getCustomAttributes())"--}}
{{--        />--}}

</x-chief::input.group>
