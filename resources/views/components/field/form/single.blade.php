@php
    $key = $key ?? null;
    $type = $type ?? null;
    $conditionalData = $toggle ?? null;

    /**
     * If a key is given the field element is populated with the
     * field values such as label, description, input value, ...
     */
    if(isset($key) || isset($field)) {
        $field = $field ?? $model->field($key);

        $label = $field->getLabel();
        $name = $field->getName();
        $type = $field->getType();
        $key = $field->getKey();
        $isRequired = $field->required();

        if($field->getDescription()) {
            $description = '<p>' . $field->getDescription() . '</p>';
        }

        $slot = ($slot == "") ? $field->render(get_defined_vars()) : $slot;

        $conditionalData = $field->getConditionalFieldsData();
    }
@endphp

<div
    data-field-key="{{ $key }}"
    data-field-type="{{ $type }}"
    {!! $conditionalData ? "data-conditional-toggle='" . json_encode($conditionalData) . "'" : null !!}
>
    {{-- Check if label exists and if it has a useful value --}}
    @if(isset($label) && $label)
        <div class="mb-1 leading-none space-x-1">
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
