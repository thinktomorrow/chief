<x-chief::form.input.prepend-append
    wire:ignore
    :prepend="isset($getPrepend) ? $getPrepend($locale ?? null) : null"
    :append="isset($getAppend) ? $getAppend($locale ?? null) : null"
>
    <x-chief::form.input.number
        id="{{ $getElementId($locale ?? null) }}"
        name="{{ $getName($locale ?? null) }}"
        placeholder="{{ $getPlaceholder($locale ?? null) }}"
        value="{{ $getActiveValue($locale ?? null) }}"
        min="{{ $getMin() ?? null }}"
        max="{{ $getMax() ?? null }}"
        step="{{ $getStep() ?? null }}"
        :autofocus="$hasAutofocus()"
        :attributes="$attributes
            ->merge($getCustomAttributes())
            ->merge([$getWireModelType() => $getWireModelValue($locale ?? null)])"
    />
</x-chief::form.input.prepend-append>
