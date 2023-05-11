@props([
    'content' => null,
    'characters' => 144
])

@if($content || $slot->isNotEmpty())
    <p {{ $attributes->class('text-sm body body-dark') }}>
        @if($content)
            {{ teaser($content, $characters, '...') }}
        @else
            {{ $slot }}
        @endif
    </p>
@endif
