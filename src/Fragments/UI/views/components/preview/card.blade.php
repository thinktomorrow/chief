@props([
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

@if($slot->isNotEmpty())
    <div {{ $attributes }} @class(['p-3 bg-grey-100 rounded-lg h-full', $spaceClass])>
        {{ $slot }}
    </div>
@endif
