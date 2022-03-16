@props([
    'label' => null,
    'required' => false,
])

<div class="w-full">
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

    <div class="{{ $label ? 'mt-2' : null }}">
        {!! $slot !!}
    </div>
</div>
