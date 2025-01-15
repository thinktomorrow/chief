@props([
    'content' => null,
    'characters' => 72
])

@if($content || $slot->isNotEmpty())
    <p {{ $attributes->class('text-sm h6 body-dark') }}>
        @if($content)
            {{ teaser($content, $characters, '...') }}
        @else
            {{ $slot }}
        @endif
    </p>
@endif
