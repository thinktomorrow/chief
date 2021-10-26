<?php

    $class = $attributes->get('class', '');

    /**
     * If a key is given the field element is populated with the
     * field values such as label, description, input value, ...
     */
    if(isset($key) || isset($field)) {
        $field = $field ?: $model->field($key);

        $label = $field->getLabel();
        $name = $field->getName();
        $isRequired = $field->required();
        $class = $field->getWidthStyle();

        if($field->getDescription()) {
            $description = '<p>' . $field->getDescription() . '</p>';
        }

        $slot = !$slot ? $field->render(get_defined_vars()) : $slot;

        // TODO(tijs): conditional defaults for field
        // data-conditional="{{ $field->getId() }}"
//        data-conditional-trigger-type="{{ $field->getType() }}"
//        data-conditional-data="{{ $field->getConditionalFieldsData() }}"
    }
?>

<div
    {!! $attributes->has('data-conditional') ? 'data-conditional="' . $attributes->get('data-conditional') . '"' : null !!}
    {!! $attributes->has('data-conditional-trigger-type') ? 'data-conditional-trigger-type="' . $attributes->get('data-conditional-trigger-type') . '"' : null !!}
    {!! $attributes->get('data-conditional-data') ? 'data-conditional-data="' . $attributes->get('data-conditional-data') . '"' : null !!}
    class="{{ $class }}"
>
    {{-- Check if label exists and if it has a useful value --}}
    @if(isset($label) && $label)
        <div class="mb-2 leading-none space-x-1">
            <span class="font-medium text-grey-700">
                {{ ucfirst($label) }}
            </span>
            @if(isset($isRequired) && ($isRequired == 'true') | $isRequired == '1')
                <span class="text-sm leading-none label label-warning">Verplicht</span>
            @endif
        </div>
    @endif

    @isset($description)
        <div class="prose prose-dark prose-editor">
            {!! $description !!}
        </div>
    @endisset

    <div class="{{ isset($label) && $label ? 'mt-3' : null }}">
        {!! $slot !!}
    </div>

    @if(isset($field))
        <x-chief::field.error :field="$field" />
    @elseif(isset($error))
        <x-chief::field.error :error="$error" />
    @endif

</div>
