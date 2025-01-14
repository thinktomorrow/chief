@props([
    'icon' => 'icon-edit',
    'color' => 'grey',
])

@php
    switch ($color) {
        case 'primary':
            $colorClasses = 'link-primary bg-grey-100 hover:bg-primary-50';
            break;
        case 'grey':
            $colorClasses = 'link-grey hover:link-primary bg-grey-100 hover:bg-primary-50';
            break;
        case 'white':
            $colorClasses = 'link-grey hover:link-primary bg-white hover:bg-primary-50';
            break;
        case 'error':
            $colorClasses = 'link-error bg-grey-100 hover:bg-red-50';
            break;
        case 'warning':
            $colorClasses = 'link-warning bg-grey-100 hover:bg-orange-50';
            break;
        default:
            $colorClasses = 'link-primary bg-grey-100 hover:bg-primary-50';
    }
@endphp

<span
    {{
        $attributes->class([
            'link inline-flex items-center gap-2 rounded-xl p-1.5 shadow-card transition-all duration-75 ease-in-out',
            '[&:hover>svg]:scale-110 [&>svg]:inline-block [&>svg]:size-5 [&>svg]:transition-all [&>svg]:duration-75 [&>svg]:ease-in-out',
            $colorClasses,
        ])
    }}
>
    @if ($slot->isNotEmpty())
        {!! $slot !!}
    @else
        <svg><use xlink:href="{{ '#' . $icon }}"></use></svg>
    @endif
</span>
