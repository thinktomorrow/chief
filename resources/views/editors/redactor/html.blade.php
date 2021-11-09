<textarea
    data-editor
    data-locale="{{ $locale ?? app()->getLocale() }}"
    name="{{ $field->getName($locale ?? null) }}"
    data-custom-redactor-options='@json($field->getHtmlOptions($key, $locale ?? null))'
    id="{{ $key }}"
    cols="10"
    rows="5"
    v-pre
>{{ old($key, $field->getValue($locale ?? null)) }}</textarea>
