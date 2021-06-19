@php
    switch($size ?? null) {
        case 'none':
            $sizeStyle = 'width: 100%;'; break;
        case 'xs':
            $sizeStyle = 'width: 90%; margin-left: auto; margin-right: auto;'; break;
        case 'sm':
            $sizeStyle = 'width: 80%; margin-left: auto; margin-right: auto;'; break;
        case 'md':
            $sizeStyle = 'width: 70%; margin-left: auto; margin-right: auto;'; break;
        case 'lg':
            $sizeStyle = 'width: 60%; margin-left: auto; margin-right: auto;'; break;
        case 'xl':
            $sizeStyle = 'width: 50%; margin-left: auto; margin-right: auto;'; break;
        default:
            $sizeStyle = 'width: 70%; margin-left: auto; margin-right: auto;';
    }

    switch($gap ?? null) {
        case 'none':
            $gapClass = ''; break;
        case 'xs':
            $gapClass = 'space-y-1'; break;
        case 'sm':
            $gapClass = 'space-y-2'; break;
        case 'md':
            $gapClass = 'space-y-4'; break;
        case 'lg':
            $gapClass = 'space-y-6'; break;
        case 'xl':
            $gapClass = 'space-y-8'; break;
        default:
            $gapClass = 'space-y-4';
    }
@endphp

<div class="{{ $gapClass }} {{ $attributes->get('class') }}" style="{{ $sizeStyle }} {{ $attributes->get('style') }}">
    {{ $slot }}
</div>
