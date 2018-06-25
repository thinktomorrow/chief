<div class="row">
    <div class="column center-y {{ isset($level) ? 'indent-'.$level : '' }}">
        {{--<i class="icon icon-menu inline text-border tree-parent"></i>--}}
        <a href="{{ route('chief.back.menu.edit', $menuitem->id) }}">{{ $menuitem->label }}</a>
    </div>

    <div class="column-4 center-y">
        @if($menuitem->type == \Thinktomorrow\Chief\Menu\MenuItem::TYPE_INTERNAL)
            <a class="label label--primary squished-xs" href="{{ $menuitem->url }}" target="_blank">{{ $menuitem->page_label }}</a>
        @else
            <a class="text-subtle" href="{{ $menuitem->url }}" target="_blank">{{ $menuitem->url }}</a>
        @endif
    </div>

    <div class="column-2 text-right">
        <a href="{{ route('chief.back.menu.edit', $menuitem->id) }}" class="btn btn-link text-font">Aanpassen</a>
    </div>
</div>