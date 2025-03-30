@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
@endphp

<x-chief::form.input.textarea
    wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
    v-pre
    id="{{ $getElementId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) }}"
    cols="5"
    rows="5"
    :autofocus="$hasAutofocus()"
    :attributes="$attributes->merge($getCustomAttributes())"
    style="resize: vertical"
>
    {{ $getActiveValue($locale ?? null) }}
</x-chief::form.input.textarea>
