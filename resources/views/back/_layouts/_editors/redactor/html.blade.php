<textarea
    data-editor
    data-locale="{{ $locale ?? app()->getLocale() }}"
    name="{{ $field->getName($locale ?? null) }}"
    id="{{ $key }}"
    cols="10"
    rows="5"
    class="w-full"
    v-pre
>{{ old($key, $field->getValue($locale ?? null)) }}</textarea>
