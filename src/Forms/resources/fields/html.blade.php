<x-chief::input.textarea
    v-pre
    data-editor
    data-locale="{{ $locale ?? app()->getLocale() }}"
    data-custom-redactor-options="{{ json_encode($getRedactorOptions($locale ?? null)) }}"
    id="{{ $getId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) }}"
    cols="10"
    rows="5"
    :autofocus="$hasAutofocus()"
    :attributes="$attributes->merge($getCustomAttributes())"
    style="resize: vertical"
>{{ $getActiveValue($locale ?? null) }}</x-chief::input.textarea>
