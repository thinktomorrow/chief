{{-- TODO: rework to icon-label using svg-symbols so we can get rid of all types --}}
@php
    $position = $position ?? 'prepend';
    $space = $space ?? 'small';

    switch($type ?? null) {
        case 'edit':
            $icon = '
                <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            '; break;
        case 'back':
            $icon = '
                <svg width="20" height="20"><use xlink:href="#icon-arrow-rtl"/></svg>
            '; break;
        case 'forward':
            $icon = '
                <svg width="20" height="20"><use xlink:href="#icon-arrow-ltr"/></svg>
            '; break;
        case 'add':
            $icon = '
                <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            '; break;
        case 'external-link':
            $icon = '
                <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            '; break;
        case 'delete':
            $icon = '
                <svg width="20" height="20"><use xlink:href="#trash"/></svg>
            '; break;
        case 'home':
            $icon = '
                <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            '; break;
        case 'settings':
            $icon = '
                <svg width="20" height="20"><use xlink:href="#settings"/></svg>
            '; break;
        case 'user':
            $icon = '
                <svg width="20" height="20"><use xlink:href="#icon-user"/></svg>
            '; break;
        default:
            $icon = '
                <svg width="20" height="20"><use xlink:href="#' . $type . '"/></svg>
            '; break;
    }
@endphp

<span class="link-label flex items-center {{ $space == 'large' ? 'space-x-4' : 'space-x-2' }} {{ $class ?? '' }}">
    @if($position == 'prepend' && isset($icon))
        <span class="link-label-icon">{!! $icon !!}</span>
    @endif

    @if($slot != '')
        <span>{{ $slot }}</span>
    @endif

    @if($position == 'append' && isset($icon))
        <span class="link-label-icon">{!! $icon !!}</span>
    @endif
</span>
