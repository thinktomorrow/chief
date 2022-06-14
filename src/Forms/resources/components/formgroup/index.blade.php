@props([
    'id',
    'label' => null,
    'description' => null,
    'required' => false,
    'fieldType' => null,
    'fieldToggles' => [],
])

<div
    data-field-key="{{ $id }}"
    data-field-type="{{ $fieldType }}"
    {!! $fieldToggles ? "data-conditional-toggle='" . json_encode($fieldToggles) . "'" : null !!}
    {{ $attributes->merge(['class' => $fieldType == 'hidden' ? 'hidden' : 'w-full']) }}
>
    @if($label)
        <div class="mb-1 leading-none">
            <span class="font-medium display-base display-dark">
                {{ ucfirst($label) }}
            </span>

            @if($required)
                <span class="leading-none text-orange-400" title="Verplicht in te vullen">*</span>
            @endif
        </div>
    @endif

    @if($description)
        <div class="mb-3 prose prose-spacing text-grey-500">
            <p>{!! $description !!}</p>
        </div>
    @endif

    <div class="{{ $label ? 'mt-2' : null }}">
        {!! $slot !!}
    </div>
</div>
