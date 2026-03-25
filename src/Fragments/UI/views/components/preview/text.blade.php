@props([
    'content' => null,
    'characters' => 144,
])

@if ($content || $slot->isNotEmpty())
    <p {{ $attributes->class('body body-dark wrap-anywhere') }}>
        @if ($content)
            {{ teaser($content, $characters, '...') }}
        @else
            {{ $slot }}
        @endif
    </p>
@endif
