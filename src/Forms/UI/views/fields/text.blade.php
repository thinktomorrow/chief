<x-chief::form.input.prepend-append
    wire:ignore
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
        ->merge([$getWireModelType() => $getWireModelValue($locale ?? null)])"
    />

    {{-- @if($isHiveEnabled()) --}}
    {{-- @include('chief-hive::suggest', ['payload' => $getHivePayload($locale ?? null, $this->getId() ? $this : null)]) --}}
    {{-- @endif --}}
</x-chief::form.input.prepend-append>

@include('chief-form::fields._partials.charactercount')
