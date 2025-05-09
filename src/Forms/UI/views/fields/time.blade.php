@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
@endphp

<x-chief::form.input.prepend-append
    :prepend="isset($getPrepend) ? $getPrepend($locale ?? null) : null"
    :append="isset($getAppend) ? $getAppend($locale ?? null) : null"
>
    <x-chief::form.input.time
        wire:model.blur="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
        id="{{ $getElementId($locale ?? null) }}"
        name="{{ $getName($locale ?? null) }}"
        placeholder="{{ $getPlaceholder($locale ?? null) }}"
        value="{{ $getActiveValue($locale ?? null) }}"
        min="{{ $getMin() ?? null }}"
        max="{{ $getMax() ?? null }}"
        step="{{ $getStep() ?? null }}"
        :autofocus="$hasAutofocus()"
        :attributes="$attributes->merge($getCustomAttributes())"
    />
</x-chief::form.input.prepend-append>
