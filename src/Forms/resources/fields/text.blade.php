<x-chief::input.prepend-append
    :prepend="isset($getPrepend) ? $getPrepend($locale ?? null) : null"
    :append="isset($getAppend) ? $getAppend($locale ?? null) : null"
>
    <x-chief::input.text
        wire:model="{{ \Thinktomorrow\Chief\Forms\Livewire\LivewireAssist::formDataIdentifier($getName(),$locale ?? null) }}"
        id="{{ $getElementId($locale ?? null) }}"
        name="{{ $getName($locale ?? null) }}"
        placeholder="{{ $getPlaceholder($locale ?? null) }}"
        value="{{ $getActiveValue($locale ?? null) }}"
        :autofocus="$hasAutofocus()"
        :attributes="$attributes->merge($getCustomAttributes())"
    />
</x-chief::input.prepend-append>
