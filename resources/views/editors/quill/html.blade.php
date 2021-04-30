<div>
    <div
        data-editor
        data-locale="{{ $locale ?? app()->getLocale() }}"
        id="{{ $field->getName($locale ?? null) }}"
    >
        {!! old($key, $field->getValue($locale ?? null)) !!}
    </div>

    <input name="{{ $field->getName($locale ?? null) }}" type="hidden">
</div>
