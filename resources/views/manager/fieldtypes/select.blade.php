@if($field->isGrouped())
    <chief-multiselect
        name="{{ isset($locale) ? $field->getName($locale) : $field->getName() }}"
        :options='@json($field->getOptions())'
        selected='@json(old($key, $field->getSelected() ?? $field->getValue($locale ?? null)))'
        :multiple='@json(!!$field->allowMultiple())'
        grouplabel="group"
        groupvalues="values"
        labelkey="label"
        valuekey="id"
    ></chief-multiselect>
@else
    <chief-multiselect
        name="{{ isset($locale) ? $field->getName($locale) : $field->getName() }}"
        :options='@json($field->getOptions())'
        selected='@json(old($key, $field->getSelected() ?? $field->getValue($locale ?? null)))'
        :multiple='@json(!!$field->allowMultiple())'
    ></chief-multiselect>
@endif
