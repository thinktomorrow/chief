@props([
    'gap' => 'md',
    'items' => 'start',
])

@php
    switch($gap) {
        case 'xs':
            $gutterClass = 'gutter-0.5'; break;
        case 'sm':
            $gutterClass = 'gutter-1'; break;
        case 'md':
            $gutterClass = 'gutter-1.5'; break;
        case 'lg':
            $gutterClass = 'gutter-2'; break;
        case 'xl':
            $gutterClass = 'gutter-3'; break;
        default:
            $gutterClass = 'gutter-1.5';
    }

    switch($items) {
        case 'stretch':
            $itemsClass = 'items-stretch'; break;
        case 'start':
            $itemsClass = 'items-start'; break;
        case 'center':
            $itemsClass = 'items-center'; break;
        case 'end':
            $itemsClass = 'items-end'; break;
        default:
            $itemsClass = 'items-stretch';
    }
@endphp

@if($slot->isNotEmpty())
    <div class="w-full">
        <div {{ $attributes }} @class([
            'flex flex-wrap justify-start w-full',
            $itemsClass,
            $gutterClass
        ])>
            {{ $slot }}
        </div>
    </div>
@endif
