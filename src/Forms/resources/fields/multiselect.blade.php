@php

    $options = [
        ['value' => 'one', 'label' => 'een'],
        ['value' => 'two', 'label' => 'twee'],
        ['value' => 'three', 'label' => 'drie'],
        ['value' => 'four', 'label' => 'vier']
    ];

@endphp

<x-chief::multiselect
    name="{{ $getName($locale ?? null) }}"
    :options="$getOptions()"
    :selection="$getActiveValue($locale ?? null)"
></x-chief::multiselect>


{{--<div data-vue-fields>--}}
{{--    <chief-multiselect--}}
{{--        name="{{ $getName($locale ?? null) }}"--}}
{{--        options='@json($getOptions())'--}}
{{--        selected='@json($getActiveValue($locale ?? null))'--}}
{{--        :multiple='@json(!!$allowMultiple())'--}}
{{--        :taggable='@json(!!$allowTaggable())'--}}
{{--        @if ($isGrouped())--}}
{{--            grouplabel="group"--}}
{{--        groupvalues="values"--}}
{{--        labelkey="label"--}}
{{--        valuekey="id"--}}
{{--        @endif--}}
{{--    />--}}
{{--</div>--}}
