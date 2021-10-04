@php
    $iconSize = (int)($size ?? 20);
    $position = $position ?? 'prepend';

    switch($color ?? null) {
        case 'primary':
            $colorClasses = 'bg-primary-50 text-primary-500 hover:bg-primary-100'; break;
        case 'secondary':
            $colorClasses = 'bg-secondary-50 text-secondary-500 hover:bg-secondary-100'; break;
        default:
            $colorClasses = 'bg-primary-50 text-primary-500 hover:bg-primary-100'; break;
    }

    switch($space ?? null) {
        case 'small':
            $spaceClass = 'space-x-2'; break;
        case 'large':
            $spaceClass = 'space-x-4'; break;
        default:
            $spaceClass = 'space-x-2';
    }

    if(isset($icon) && strpos($icon, '<svg ') === false) {
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

<span class="p-2 -m-2 rounded-xl font-medium icon-label inline-flex items-center {{ $colorClasses }} {{ $class ?? null }}">
    {{-- min-height set to 20px as this is the height of text with leading-tight (16px * 1.25) --}}
    <span class="inline-flex items-center justify-center {{ $spaceClass }}" style="min-width: 20px; min-height: 20px">
        @if($position == 'prepend' && isset($iconElement))
            <span class="icon-label-icon">{!! $iconElement !!}</span>
        @endif

        @if($slot != '')
            <span data-icon-label class="leading-tight">{{ $slot }}</span>
        @endif

        @if($position == 'append' && isset($iconElement))
            <span class="icon-label-icon">{!! $iconElement !!}</span>
        @endif
    </span>
</span>
