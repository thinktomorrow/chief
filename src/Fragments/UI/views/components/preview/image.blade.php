@props([
    'src' => null,
    'objectFit' => 'contain'
])

@php
    // If argument isn't contain or cover, set to default contain
    if($objectFit != 'contain' && $objectFit != 'cover') {
        $objectFit = 'contain';
    }
@endphp

@if($src)
    <a
        href="{{ $src }}"
        title="Chief fragment preview image"
        target="_blank"
        rel="noopener"
        {{ $attributes->class(['block w-full bg-grey-100 rounded-lg overflow-hidden']) }}
    >
        <img src="{{ $src }}" alt="Chief fragment preview image" @class([
            'w-full max-h-40',
            'object-cover' => $objectFit == 'cover',
            'object-contain' => $objectFit == 'contain',
        ])>
    </a>
@endif
