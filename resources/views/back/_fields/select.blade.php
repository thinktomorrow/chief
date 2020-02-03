@if($field->isGrouped())
    <chief-multiselect
            name="{{ isset($locale) ? $field->getName($locale) : $field->getName() }}"
            :options='@json($field->options)'
            selected='@json(old($key, $field->selected ?? $manager->fieldValue($field, $locale ?? null)))'
            :multiple='@json(!!$field->allowMultiple())'
            grouplabel="group"
            groupvalues="values"
            labelkey="label"
            valuekey="id"
    >
    </chief-multiselect>
@else
    <chief-multiselect
            name="{{ isset($locale) ? $field->getName($locale) : $field->getName() }}"
            :options='@json($field->options)'
            selected='@json(old($key, $field->selected ?? $manager->fieldValue($field, $locale ?? null)))'
            :multiple='@json(!!$field->allowMultiple())'
    >
    </chief-multiselect>
@endif

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
