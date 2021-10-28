<input
        type="hidden"
        name="{{ $field->getName($locale ?? null) }}"
        id="{{ $field->getId($locale ?? null) }}"
        value="{{ old($field->getDottedName($locale ?? null), $field->getValue($locale ?? null)) }}"
>
