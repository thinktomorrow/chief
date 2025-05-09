@props([
    'content' => null,
    'characters' => 72,
])

@if ($content || $slot->isNotEmpty())
    <p {{ $attributes->class('h6 body-dark') }}>
        @if ($content)
            {{ teaser($content, $characters, '...') }}
        @else
            {{ $slot }}
        @endif
    </p>
@endif
