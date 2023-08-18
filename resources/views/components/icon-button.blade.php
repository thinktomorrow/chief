@props([
    'icon' => 'icon-edit',
    'color' => 'grey',
])

@php
    switch($color) {
        case 'primary':
            $colorClasses = 'link-primary bg-grey-100 hover:bg-primary-50';
            break;
        case 'grey':
            $colorClasses = 'link-grey bg-grey-100 hover:link-primary hover:bg-primary-50';
            break;
        case 'white':
            $colorClasses = 'link-grey bg-white hover:link-primary hover:bg-primary-50';
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

<span {{
    $attributes
        ->merge(['class' => 'inline-flex items-center p-1.5 rounded-xl link gap-2 hover:child-svg-scale-110 transition-all duration-75 ease-in-out shadow-card'])
        ->merge(['class' => $colorClasses])
}}>
    @if($slot->isNotEmpty())
        {!! $slot !!}
    @else
        <svg class="w-5 h-5"><use xlink:href="#{{ $icon }}"></use></svg>
    @endif
</span>
