@php

    //    $options = [
    //        ['value' => 'one', 'label' => 'een'],
    //        ['value' => 'two', 'label' => 'twee'],
    //        ['value' => 'three', 'label' => 'drie'],
    //        ['value' => 'four', 'label' => 'vier']
    //    ];
    //
    ////    $options = [
    ////        'one' => 'een',
    ////        'two' => 'twee',
    ////        'three' => 'drie',
    ////        'four' => 'vier',
    ////];
    //
    $options = [
        [
            'label' => 'eerste group',
            'id' => 'one-group',
            'options' => [
                 'one' => 'een',
                'two' => 'twee',
                'three' => 'drie',
                'four' => 'vier',
            ],
        ],
        [
            'label' => 'tweede group',
             'id' => 'two-group',
            'options' => [
                'three' => 'drie',
                'four' => 'vier',
            ],
        ],
    ];

        $component->options($options)->value('three')->multiple();

@endphp

<x-chief::multiselect
    name="{{ $getName($locale ?? null) . ($allowMultiple() ? '[]' : '') }}"
    :options="$getOptions()"
    :multiple="$allowMultiple()"
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
