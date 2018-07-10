<div class="row">
    <div class="column center-y {{ isset($level) ? 'indent-'.$level : '' }}">
        {{--<i class="icon icon-menu inline text-border tree-parent"></i>--}}
        @if(isset($level) && $level > 0)
            <span class="icon icon-arrow-right text-border inline-s tree-parent"></span>
        @endif
        <a href="{{ route('chief.back.menu.edit', $item->id) }}">{{ $item->label }}</a>
    </div>

    <div class="column-4 center-y">
        @if($item->type == \Thinktomorrow\Chief\Menu\MenuItem::TYPE_INTERNAL)
            <a class="label label--primary squished-xs" href="{{ $item->url }}" target="_blank">{{ $item->page_label }}</a>
        @elseif($item->type == \Thinktomorrow\Chief\Menu\MenuItem::TYPE_NOLINK)
            -
        @else
            <a class="text-subtle" href="{{ $item->url }}" target="_blank">{{ $item->url }}</a>
        @endif
    </div>

    <div class="column-2 text-right">
        <a href="{{ route('chief.back.menu.edit', $item->id) }}" class="btn btn-link text-font">Aanpassen</a>
    </div>
</div>