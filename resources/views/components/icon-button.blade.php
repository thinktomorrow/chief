@props([
    'icon' => 'icon-edit',
    'color' => 'primary',
])

@php
    switch($color) {
        case 'primary':
            $colorClasses = 'link-primary hover:bg-primary-50';
            break;
        case 'grey':
            $colorClasses = 'link-grey hover:link-primary hover:bg-primary-50';
            break;
        case 'white':
            $colorClasses = 'link-grey hover:link-primary hover:bg-primary-50';
            break;
        case 'error':
            $colorClasses = 'link-error hover:bg-red-50';
            break;
        default:
            $colorClasses = 'link-primary hover:bg-primary-50';
    }
@endphp

<span {{
    $attributes
        ->merge(['class' => 'inline-flex items-center p-1.5 rounded-xl link space-x-2 hover:child-svg-scale-110 transition-all duration-75 ease-in-out'])
        ->merge(['class' => $colorClasses])
}} >
    @if($slot->isNotEmpty())
        {!! $slot !!}
    @else
        <svg class="w-5 h-5"><use xlink:href="#{{ $icon }}"></use></svg>
    @endif
</span>
