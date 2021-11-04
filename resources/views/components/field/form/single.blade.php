@php
    /**
     * If a key is given the field element is populated with the
     * field values such as label, description, input value, ...
     */
    if(isset($key) || isset($field)) {
        $field = $field ?? $model->field($key);

        $label = $field->getLabel();
        $name = $field->getName();
        $isRequired = $field->required();

        if($field->getDescription()) {
            $description = '<p>' . $field->getDescription() . '</p>';
        }

        $slot = ($slot == "") ? $field->render(get_defined_vars()) : $slot;

        // TODO(tijs): conditional defaults for field
        // data-conditional="{{ $field->getId() }}"
        // data-conditional-trigger-type="{{ $field->getType() }}"
        // data-conditional-data="{{ $field->getConditionalFieldsData() }}"
    }
@endphp

<div
    {!! $attributes->has('data-conditional') ? 'data-conditional="' . $attributes->get('data-conditional') . '"' : null !!}
    {!! $attributes->has('data-conditional-trigger-type') ? 'data-conditional-trigger-type="' . $attributes->get('data-conditional-trigger-type') . '"' : null !!}
    {!! $attributes->get('data-conditional-data') ? 'data-conditional-data="' . $attributes->get('data-conditional-data') . '"' : null !!}
    class="{{ $attributes->get('class', '') }}"
>
    {{-- Check if label exists and if it has a useful value --}}
    @if(isset($label) && $label)
        <div class="mb-1 space-x-1 leading-none">
            <span class="font-medium display-base display-dark">
                {{ ucfirst($label) }}
            </span>

            @if(isset($isRequired) && ($isRequired == 'true') | $isRequired == '1')
                <span class="leading-none label label-xs label-warning">Verplicht</span>
            @endif
        </div>
    @endif

    @isset($description)
        <div class="mb-3 prose prose-dark prose-editor">
            {!! $description !!}
        </div>
    @endisset

    <div class="{{ isset($label) && $label ? 'mt-2' : null }}">
        {!! $slot !!}
    </div>

    @if(isset($field))
        <x-chief::field.form.error :field="$field" />
    @elseif(isset($error))
        <x-chief::field.form.error :error="$error" />
    @endif
</div>
