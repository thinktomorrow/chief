@props([
    'id',
    'label' => null,
    'description' => null,
    'required' => false,

    'fieldType' => null,
    'fieldToggles' => [],
])

<div
        class="w-full"
        data-field-key="{{ $id }}"
        data-field-type="{{ $fieldType }}"
        {!! $fieldToggles ? "data-conditional-toggle='" . json_encode($fieldToggles) . "'" : null !!}
>
    @if($label)
        <div class="mb-1 space-x-1 leading-none">
            <span class="font-medium display-base display-dark">
                {{ ucfirst($label) }}
            </span>

            @if($required)
                <span class="leading-none text-orange-400" title="Verplicht in te vullen">*</span>
            @endif
        </div>
    @endif

    @if($description)
        <div class="mb-3 prose prose-dark prose-editor text-grey-600">
            {!! $description !!}
        </div>
    @endif

    <div class="{{ $label ? 'mt-2' : null }}">
        {!! $slot !!}
    </div>
</div>
