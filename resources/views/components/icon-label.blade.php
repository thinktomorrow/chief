@php
    $position = $position ?? 'prepend';
    $space = $space ?? 'small';

    if(isset($icon)) {
        $iconElement = '<svg width="20" height="20"><use xlink:href="#' . $icon . '"/></svg>';
    } else {
        switch($type ?? null) {
            case 'edit':
                $iconElement = '<svg width="20" height="20"><use xlink:href="#icon-edit"/></svg>'; break;
            case 'back':
                $iconElement = '<svg width="20" height="20"><use xlink:href="#icon-arrow-rtl"/></svg>'; break;
            case 'forward':
                $iconElement = '<svg width="20" height="20"><use xlink:href="#icon-arrow-ltr"/></svg>'; break;
            case 'add':
                $iconElement = '<svg width="20" height="20"><use xlink:href="#icon-add"/></svg>'; break;
            case 'external-link':
                $iconElement = '<svg width="20" height="20"><use xlink:href="#icon-external-link"/></svg>'; break;
            case 'delete':
                $iconElement = '<svg width="20" height="20"><use xlink:href="#trash"/></svg>'; break;
        }
    }
@endphp

<span class="link-label inline-flex items-center leading-none {{ $space == 'large' ? 'space-x-4' : 'space-x-2' }} {{ $class ?? '' }}">
    @if($position == 'prepend' && isset($iconElement))
        <span class="link-label-icon">{!! $iconElement !!}</span>
    @endif

    @if($slot != '')
        <span>{{ $slot }}</span>
    @endif

    @if($position == 'append' && isset($iconElement))
        <span class="link-label-icon">{!! $iconElement !!}</span>
    @endif
</span>
