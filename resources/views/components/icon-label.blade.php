@php
    $iconSize = (int)($size ?? 20);
    $position = $position ?? 'prepend';

    switch($space ?? null) {
        case 'small':
            $spaceClass = 'space-x-2'; break;
        case 'large':
            $spaceClass = 'space-x-4'; break;
        default:
            $spaceClass = 'space-x-2'; break;
    }

    if(isset($icon) && false === strpos($icon, '<svg ')) {
        $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#' . $icon . '"/></svg>';
    } elseif(isset($icon)) {
        $iconElement = $icon;
    } else {
        switch($type ?? null) {
            case 'back':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#icon-arrow-rtl"/></svg>'; break;
            case 'forward':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#icon-arrow-ltr"/></svg>'; break;
            case 'add':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#icon-add"/></svg>'; break;
            case 'delete':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#trash"/></svg>'; break;
            case 'edit':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#icon-edit"/></svg>'; break;
            case 'close':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#x"/></svg>'; break;
            default: break;
        }
    }
@endphp

<span class="icon-label inline-flex items-center {{ $spaceClass }} {{ $class ?? '' }}" style="min-height: {{ $iconSize }}px">
    @if($position == 'prepend' && isset($iconElement))
        <span class="icon-label-icon">{!! $iconElement !!}</span>
    @endif

    @if($slot != '')
        <span data-icon-label class="leading-none">{{ $slot }}</span>
    @endif

    @if($position == 'append' && isset($iconElement))
        <span class="icon-label-icon">{!! $iconElement !!}</span>
    @endif
</span>

{{-- @php
    $iconSize = $size ?? 20;
    $position = $position ?? 'prepend';

    switch($space ?? null) {
        case 'small':
            $spaceClass = 'space-x-2'; break;
        case 'large':
            $spaceClass = 'space-x-4'; break;
        default:
            $spaceClass = 'space-x-2'; break;
    }

    if(isset($icon) && false === strpos($icon, '<svg ')) {
        $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#' . $icon . '"/></svg>';
    } elseif(isset($icon)) {
        $iconElement = $icon;
    } else {
        switch($type ?? null) {
            case 'back':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#icon-arrow-rtl"/></svg>'; break;
            case 'forward':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#icon-arrow-ltr"/></svg>'; break;
            case 'add':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#icon-add"/></svg>'; break;
            case 'delete':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#trash"/></svg>'; break;
            case 'edit':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#icon-edit"/></svg>'; break;
            case 'close':
                $iconElement = '<svg width="' . $iconSize . '" height="' . $iconSize . '"><use xlink:href="#x"/></svg>'; break;
            default: break;
        }
    }
@endphp

<span class="icon-label inline-flex items-center {{ $spaceClass }} {{ $class ?? '' }}" style="min-height: {{ $iconSize }}px">
    @if($position == 'prepend' && isset($iconElement))
        <span class="icon-label-icon">{!! $iconElement !!}</span>
    @endif

    @if($slot != '')
        <span data-icon-label class="leading-none">{{ $slot }}</span>
    @endif

    @if($position == 'append' && isset($iconElement))
        <span class="icon-label-icon">{!! $iconElement !!}</span>
    @endif
</span> --}}
