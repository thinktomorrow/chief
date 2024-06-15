@props([
    'width' => null,
    'gap' => 'md'
])

@php
    switch($gap) {
        case 'xs':
            $spaceClass = 'space-y-0.5'; break;
        case 'sm':
            $spaceClass = 'space-y-1'; break;
        case 'md':
            $spaceClass = 'space-y-1.5'; break;
        case 'lg':
            $spaceClass = 'space-y-2'; break;
        case 'xl':
            $spaceClass = 'space-y-3'; break;
        default:
            $spaceClass = 'space-y-1.5';
    }
@endphp

<div {{ $attributes }} @class([
    'w-full',
    'sm:w-1/2' => $width == '1/2',
    'sm:w-1/2 md:w-1/3' => $width == '1/3',
    'sm:w-1/2 md:w-2/3' => $width == '2/3',
    $spaceClass
])>
    {{ $slot }}
</div>
