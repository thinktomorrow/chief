@props([
    'label' => null,
])

@if ($label || $slot->isNotEmpty())
    <div {{ $attributes->merge(['class' => 'max-w-80 space-y-1 col-start-2']) }}>
        @if ($label)
            <p>{{ $label }}</p>
        @endif

        @if ($slot->isNotEmpty())
            <div class="prose-format prose-editor text-sm text-wrap">
                {{ $slot }}
            </div>
        @endif
    </div>
@endif
