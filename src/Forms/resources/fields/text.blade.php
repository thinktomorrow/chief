<x-chief-forms::formgroup.prepend-append
    :prepend="$getPrepend($locale ?? null)"
    :append="$getAppend($locale ?? null)"
>
    <input
        type="{{ $inputType ?? 'text' }}"
        name="{{ $getName($locale ?? null) }}"
        id="{{ $getElementId($locale ?? null) }}"
        placeholder="{{ $getPlaceholder($locale ?? null) }}"
        value="{{ $getActiveValue($locale ?? null) }}"
        {{ $hasAutofocus() ? 'autofocus' : '' }}
    >
</x-chief-forms::formgroup.prepend-append>
