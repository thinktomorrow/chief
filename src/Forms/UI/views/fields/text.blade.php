@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
@endphp

<x-chief::form.input.prepend-append
    :prepend="isset($getPrepend) ? $getPrepend($locale ?? null) : null"
    :append="isset($getAppend) ? $getAppend($locale ?? null) : null"
>
    <x-chief::form.input.text
        wire:model.blur="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
        id="{{ $getElementId($locale ?? null) }}"
        name="{{ $getName($locale ?? null) }}"
        placeholder="{{ $getPlaceholder($locale ?? null) }}"
        value="{{ $getActiveValue($locale ?? null) }}"
        :autofocus="$hasAutofocus()"
        :attributes="$attributes->merge($getCustomAttributes())"
    />

    @if($isHiveEnabled())
        @include('chief-hive::suggest', ['payload' => $getHivePayload($locale ?? null, $this->getId() ? $this : null)])
    @endif
</x-chief::form.input.prepend-append>
