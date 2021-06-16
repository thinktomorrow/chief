@php
    switch($gap ?? null) {
        case 'none':
            $gapClass = ''; break;
        case 'xs':
            $gapClass = 'gutter-1'; break;
        case 'sm':
            $gapClass = 'gutter-2'; break;
        case 'md':
            $gapClass = 'gutter-4'; break;
        case 'lg':
            $gapClass = 'gutter-6'; break;
        case 'xl':
            $gapClass = 'gutter-8'; break;
        default:
            $gapClass = 'gutter-2';
    }

    switch($justify ?? null) {
        case 'start':
            $justifyClass = 'justify-start'; break;
        case 'center':
            $justifyClass = 'justify-center'; break;
        case 'between':
            $justifyClass = 'justify-between'; break;
        case 'end':
            $justifyClass = 'justify-end'; break;
        default:
            $justifyClass = 'justify-start';
    }

    switch($items ?? null) {
        case 'start':
            $itemsClass = 'items-start'; break;
        case 'center':
            $itemsClass = 'items-center'; break;
        case 'between':
            $itemsClass = 'items-between'; break;
        case 'end':
            $itemsClass = 'items-end'; break;
        default:
            $itemsClass = 'items-start';
    }
@endphp

{{-- Extra wrapper div so wireframe container vertical gap will never be in conflict with gutter negative margins --}}
<div>
    <div
        class="flex flex-wrap {{ $justifyClass }} {{ $itemsClass }} {{ $gapClass }}"
        style="{{ $attributes->get('style') }}"
    >
        {{ $slot }}
    </div>
</div>
