<x-chief::input.prepend-append
    :prepend="isset($getPrepend) ? $getPrepend($locale ?? null) : null"
    :append="isset($getAppend) ? $getAppend($locale ?? null) : null"
>
    <x-chief::input.text
        {{-- TODO: FINISH --}}
        {{-- :attributes="$attributes" --}}
        name="{{ $getName($locale ?? null) }}"
        id="{{ $getElementId($locale ?? null) }}"
        placeholder="{{ $getPlaceholder($locale ?? null) }}"
        value="{{ $getActiveValue($locale ?? null) }}"
        {{ $hasAutofocus() ? 'autofocus' : '' }}
        {{ $attributes->merge($getCustomAttributes())->merge($passedAttributes ?? [])->merge([
            'type' => 'text',
        ]) }}
    >
</x-chief::input.prepend-append>
