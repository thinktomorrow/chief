<span class="inline-flex items-center p-1.5 rounded-xl link link-primary space-x-2 bg-primary-50 hover:child-svg-scale-110">
    @if($slot->isNotEmpty())
        {!! $slot !!}
    @else
        <svg width="18" height="18"><use xlink:href="#{{ $icon ?? 'icon-edit' }}"></use></svg>
    @endif
</span>
