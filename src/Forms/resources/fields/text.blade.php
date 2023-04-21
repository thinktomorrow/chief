<x-chief-form::formgroup.prepend-append
    :prepend="isset($getPrepend) ? $getPrepend($locale ?? null) : null"
    :append="isset($getAppend) ? $getAppend($locale ?? null) : null"
>
    <input
        wire:model="{{ \Thinktomorrow\Chief\Forms\Livewire\LivewireAssist::formDataIdentifier($getName(),$locale ?? null) }}"
        name="{{ $getName($locale ?? null) }}"
{{--        id="{{ $getElementId($locale ?? null) }}"--}}
        placeholder="{{ $getPlaceholder($locale ?? null) }}"
        value="{{ $getActiveValue($locale ?? null) }}"
        {{ $hasAutofocus() ? 'autofocus' : '' }}
        {{ $attributes->merge($getCustomAttributes())->merge($passedAttributes ?? [])->merge([
            'type' => 'text',
        ]) }}
    >
</x-chief-form::formgroup.prepend-append>
