<input
    type="tel"
    name="{{ $field->getName($locale ?? null) }}"
    id="{{ $key }}"
    value="{{ old($key, $field->getValue($locale ?? null)) }}"
>
