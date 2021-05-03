@if($field->isGrouped())
    <chief-multiselect
        name="{{ $field->getName($locale ?? null) }}"
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
        name="{{ $field->getName($locale ?? null) }}"
        :options='@json($field->getOptions())'
        selected='@json(old($key, $field->getSelected() ?? $field->getValue($locale ?? null)))'
        :multiple='@json(!!$field->allowMultiple())'
    ></chief-multiselect>
@endif
