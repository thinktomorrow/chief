<input
    type="number"
    step="{{ $field->getSteps() }}"
    min="{{ $field->getMin() }}"
    max="{{ $field->getMax() }}"
    name="{{ $field->getName($locale ?? null) }}"
    id="{{ $key }}"
    value="{{ old($key, $field->getValue($locale ?? null)) }}"
>
