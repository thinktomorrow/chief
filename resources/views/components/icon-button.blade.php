@php
    switch($color ?? 'primary') {
        case 'primary':
            $colorClasses = 'link-primary bg-primary-50';
            break;
        case 'grey':
            $colorClasses = 'link-grey bg-grey-100 hover:link-primary hover:bg-primary-50';
            break;
        default:
            $colorClasses = 'link-primary bg-primary-50';
    }
@endphp

<span {{
    $attributes
        ->merge(['class' => 'inline-flex items-center p-1.5 rounded-xl link space-x-2 hover:child-svg-scale-110'])
        ->merge(['class' => $colorClasses])
}} >
    @if($slot->isNotEmpty())
        {!! $slot !!}
    @else
        <svg width="18" height="18"><use xlink:href="#{{ $icon ?? 'icon-edit' }}"></use></svg>
    @endif
</span>
