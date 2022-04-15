@props([
    'src' => null,
    'position' => 'center',
])

@php
    switch($position) {
        case 'left':
            $positionClass = 'justify-start'; break;
        case 'center':
            $positionClass = 'justify-center'; break;
        default:
            $positionClass = 'justify-start';
    }
@endphp

@if($src)
    <a
        href="{{ $src }}"
        title="Chief fragment preview image"
        target="_blank"
        rel="noopener"
        {{ $attributes->class(['w-full bg-grey-100 rounded-lg flex overflow-hidden', $positionClass]) }}
    >
        <img src="{{ $src }}" alt="Chief fragment preview image">
    </a>
@endif
