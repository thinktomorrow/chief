<x-chief::form.input.prepend-append
    wire:ignore
    :prepend="isset($getPrepend) ? $getPrepend($locale ?? null) : null"
    :append="isset($getAppend) ? $getAppend($locale ?? null) : null"
>
    <x-chief::form.input.time
        id="{{ $getElementId($locale ?? null) }}"
        name="{{ $getName($locale ?? null) }}"
        value="{{ $getActiveValue($locale ?? null) }}"
        :min="$getMin()"
        :max="$getMax()"
        :step="$getStep()"
        :autofocus="$hasAutofocus()"
        :attributes="$attributes
            ->merge($getCustomAttributes())
            ->merge([$getWireModelType() => $getWireModelValue($locale ?? null)])"
    />
</x-chief::form.input.prepend-append>
