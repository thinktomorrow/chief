@php
    switch($size ?? null) {
        case 'xs':
            $sizeClass = 'h-8'; break;
        case 'sm':
            $sizeClass = 'h-16'; break;
        case 'md':
            $sizeClass = 'h-32'; break;
        case 'lg':
            $sizeClass = 'h-48'; break;
        case 'xl':
            $sizeClass = 'h-64'; break;
        default:
            $sizeClass = 'h-32';
    }
@endphp

@unless($slot->isEmpty())
    <div class="{{ $sizeClass }} bg-center bg-no-repeat bg-cover rounded-lg" style="background-image: url('{{ $slot }}');"></div>
@else
    <div class="h-32 rounded-lg bg-grey-300"></div>
@endunless
