<div @if(!$field->prefersNativeSelect()) data-vue-fields @endif>
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
        <div class="relative flex items-center justify-end cursor-pointer">
            <select
                {{ $field->allowMultiple() ? 'multiple' : '' }}
                name="{{ isset($locale) ? $field->getName($locale) : $field->getName() }}"
                id="{{ $field->getId($locale ?? null) }}"
                class="select"
            >
                <option value="">---</option>

                @foreach($field->getOptions() as $key => $value)
                    <option
                        {{ in_array($key, (array) old($key, $field->getSelected() ?? $field->getValue($locale ?? null))) ? 'selected' : '' }}
                        value="{{ $key }}"
                    >{{ $value }}</option>
                @endforeach
            </select>

            <span class="absolute pr-3 pointer-events-none">
                <svg width="16" height="16" class="text-grey-700"><use xlink:href="#icon-chevron-down"></use></svg>
            </span>
        </div>
    @else
        <chief-multiselect
            name="{{ isset($locale) ? $field->getName($locale) : $field->getName() }}"
            :options='@json($field->getOptions())'
            selected='@json(old($key, $field->getSelected() ?? $field->getValue($locale ?? null)))'
            :multiple='@json(!!$field->allowMultiple())'
        ></chief-multiselect>
    @endif
</div>
