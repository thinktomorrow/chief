@php use Thinktomorrow\Chief\Forms\Livewire\LivewireFieldName; @endphp
<x-chief::input.prepend-append
        :prepend="isset($getPrepend) ? $getPrepend($locale ?? null) : null"
        :append="isset($getAppend) ? $getAppend($locale ?? null) : null"
>
    <x-chief::input.text
            wire:model.lazy="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
            id="{{ $getElementId($locale ?? null) }}"
            name="{{ $getName($locale ?? null) }}"
            placeholder="{{ $getPlaceholder($locale ?? null) }}"
            value="{{ $getActiveValue($locale ?? null) }}"
            :autofocus="$hasAutofocus()"
            :attributes="$attributes->merge($getCustomAttributes())"
    />
</x-chief::input.prepend-append>
