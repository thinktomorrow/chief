@php
    $modelBindingType = $getWireModelType() == 'defer' ? 'wire:model' : 'wire:model.' . $getWireModelType();
    $wireBindingValue = Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName::get($getName($locale ?? null));
@endphp

<x-chief::form.input.prepend-append
    wire:key="{{ $wireBindingValue }}"
    :prepend="isset($getPrepend) ? $getPrepend($locale ?? null) : null"
    :append="isset($getAppend) ? $getAppend($locale ?? null) : null"
>
    <x-chief::form.input.text
        id="{{ $getElementId($locale ?? null) }}"
        name="{{ $getName($locale ?? null) }}"
        placeholder="{{ $getPlaceholder($locale ?? null) }}"
        value="{{ $getActiveValue($locale ?? null) }}"
        :autofocus="$hasAutofocus()"
        :attributes="$attributes
            ->merge($getCustomAttributes())
            ->merge([$modelBindingType => $wireBindingValue])"
    />

    {{--    @if($isHiveEnabled())--}}
    {{--        @include('chief-hive::suggest', ['payload' => $getHivePayload($locale ?? null, $this->getId() ? $this : null)])--}}
    {{--    @endif--}}
</x-chief::form.input.prepend-append>
