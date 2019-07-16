@if($field->grouped())
    <chief-multiselect
            name="{{ isset($locale) ? $field->translateName($locale) : $field->name() }}"
            :options='@json($field->options)'
            selected='@json(old($key, $field->selected ?? $manager->fieldValue($field, $locale ?? null)))'
            :multiple='@json(!!$field->multiple)'
            grouplabel="group"
            groupvalues="values"
            labelkey="label"
            valuekey="id"
    >
    </chief-multiselect>
@else
    <chief-multiselect
            name="{{ isset($locale) ? $field->translateName($locale) : $field->name() }}"
            :options='@json($field->options)'
            selected='@json(old($key, $field->selected ?? $manager->fieldValue($field, $locale ?? null)))'
            :multiple='@json(!!$field->multiple)'
    >
    </chief-multiselect>
@endif

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
