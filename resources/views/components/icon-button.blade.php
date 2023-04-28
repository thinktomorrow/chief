@props([
    'icon' => 'icon-edit',
    'color' => 'grey',
])

@php
    switch($color) {
        case 'grey':
            $colorClasses = 'link-grey hover:link-primary hover:bg-primary-50';
            break;
        case 'primary':
            $colorClasses = 'link-primary hover:bg-primary-50';
            break;
        case 'white':
            $colorClasses = 'link-grey hover:link-primary hover:bg-primary-50';
            break;
        case 'error':
            $colorClasses = 'link-error hover:bg-red-50';
            break;
        default:
            $colorClasses = 'link-grey hover:link-primary hover:bg-primary-50';
    }
@endphp

<span {{
    $attributes
        ->merge(['class' => 'inline-flex justify-center items-center w-8 h-8 rounded-xl link transition-all duration-100 ease-in-out'])
        ->merge(['class' => '[&:hover>svg]:scale-105 [&:hover>svg]:transition-all [&:hover>svg]:duration-75 [&:hover>svg]:ease-in-out'])
        ->merge(['class' => $colorClasses])
}} >
    @if($slot->isNotEmpty())
        {!! $slot !!}
    @else
        <svg class="w-5 h-5"><use xlink:href="#{{ $icon }}"></use></svg>
    @endif
</span>
