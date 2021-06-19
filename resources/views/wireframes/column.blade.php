@php
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

<div class="{{ $gapClass }} {{ $attributes->get('class') }}" style="width: {{ $width ?? '100%' }}; {{ $attributes->get('style') }}">
    {{ $slot }}
</div>
