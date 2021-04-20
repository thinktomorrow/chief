@php
    $iconSize = 20;
    $position = $position ?? 'prepend';

    switch($space ?? null) {
        case 'small':
            $spaceClass = 'space-x-2'; break;
        case 'large':
            $spaceClass = 'space-x-4'; break;
        default:
            $spaceClass = 'space-x-2'; break;
    }

    if(isset($icon)) {
        $iconElement = '<svg width="20" height="20"><use xlink:href="#' . $icon . '"/></svg>';
    } else {
        switch($type ?? null) {
            case 'edit':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#icon-edit"/></svg>'; break;
            case 'back':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#icon-arrow-rtl"/></svg>'; break;
            case 'forward':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#icon-arrow-ltr"/></svg>'; break;
            case 'add':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#icon-add"/></svg>'; break;
            case 'external-link':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#icon-external-link"/></svg>'; break;
            case 'delete':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#trash"/></svg>'; break;
            default: break;
        }
    }
@endphp

<span class="icon-label inline-flex items-center {{ $spaceClass }} {{ $class ?? '' }}" style="min-height: {{ $iconSize }}px">
    @if($position == 'prepend' && isset($iconElement))
        <span class="icon-label-icon">{!! $iconElement !!}</span>
    @endif

    @if($slot != '')
        <span class="leading-none">{{ $slot }}</span>
    @endif

    @if($position == 'append' && isset($iconElement))
        <span class="icon-label-icon">{!! $iconElement !!}</span>
    @endif
</span>
