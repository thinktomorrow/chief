@php
    $modelBindingType = $getWireModelType() == 'defer' ? 'wire:model' : 'wire:model.' . $getWireModelType();
    $modelBinding = [$modelBindingType => Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName::get($getName($locale ?? null))];
@endphp

<x-chief::form.input.textarea
    v-pre
    id="{{ $getElementId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) }}"
    cols="5"
    rows="5"
    :autofocus="$hasAutofocus()"
    :attributes="$attributes
            ->merge($getCustomAttributes())
            ->merge($modelBinding)"
    style="resize: vertical"
>
    {{ $getActiveValue($locale ?? null) }}
</x-chief::form.input.textarea>
