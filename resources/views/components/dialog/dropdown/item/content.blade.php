@props([
    'label' => null,
])

@if ($label || $slot->isNotEmpty())
    <div class="max-w-80 space-y-1">
        @if ($label)
            <p>{{ $label }}</p>
        @endif

        @if ($slot->isNotEmpty())
            <div class="prose-format prose-editor prose-size-sm text-wrap text-grey-500">
                {{ $slot }}
            </div>
        @endif
    </div>
@endif
