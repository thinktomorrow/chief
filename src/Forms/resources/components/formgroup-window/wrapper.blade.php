@props([
    'label' => null,
])

<div class="w-full">
    @if($label)
        <div class="mb-1 space-x-1 leading-none">
            <span class="font-medium display-base display-dark">
                {{ ucfirst($label) }}
            </span>
        </div>
    @endif

    <div class="{{ $label ? 'mt-2' : null }}">
        {!! $slot !!}
    </div>
</div>
