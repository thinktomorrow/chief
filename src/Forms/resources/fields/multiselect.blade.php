<div data-vue-fields>
    <chief-multiselect
        name="{{ $getName($locale ?? null) }}"
        options='@json($getOptions())'
        selected='@json($getActiveValue($locale ?? null))'
        :multiple='@json(!!$allowMultiple())'
        :taggable='@json(!!$allowTaggable())'
        @if ($isGrouped())
            grouplabel="group"
            groupvalues="values"
            labelkey="label"
            valuekey="id"
        @endif
    />
</div>
