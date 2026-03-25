@props([
    'content' => null,
    'characters' => 72,
])

@if ($content || $slot->isNotEmpty())
    <p {{ $attributes->class('h6 body-dark wrap-anywhere') }}>
        @if ($content)
            {{ teaser($content, $characters, '...') }}
        @else
            {{ $slot }}
        @endif
    </p>
@endif
