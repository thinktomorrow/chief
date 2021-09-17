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
@elseif($field->prefersNativeSelect())
    <select
            name="{{ isset($locale) ? $field->getName($locale) : $field->getName() }}"
            id="{{ $field->getId($locale ?? null) }}">
            <option value="">---</option>
        @foreach($field->getOptions() as $key => $value)
            <option {{ in_array($key, (array) old($key, $field->getSelected() ?? $field->getValue($locale ?? null))) ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
@else
    <chief-multiselect
        name="{{ isset($locale) ? $field->getName($locale) : $field->getName() }}"
        :options='@json($field->getOptions())'
        selected='@json(old($key, $field->getSelected() ?? $field->getValue($locale ?? null)))'
        :multiple='@json(!!$field->allowMultiple())'
    ></chief-multiselect>
@endif
