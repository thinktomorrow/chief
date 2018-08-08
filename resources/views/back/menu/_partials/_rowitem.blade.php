<div class="row">
    <div class="column center-y {{ isset($level) ? 'indent-'.$level : '' }}">
        {{--<i class="icon icon-menu inline text-border tree-parent"></i>--}}
        @if(isset($level) && $level > 0)
            <span class="icon icon-arrow-right text-border inline-s tree-parent"></span>
        @endif

        @if($item->auto_generated)
            {{ $item->label }}
        @else
            <a href="{{ route('chief.back.menu.edit', $item->id) }}">{{ $item->label }}</a>
            @if($item->type == \Thinktomorrow\Chief\Menu\MenuItem::TYPE_COLLECTION)
                &nbsp;<em class="text-subtle">pagina groep</em>
            @endif
        @endif
    </div>

    <div class="column-4 center-y">
        @if($item->type == \Thinktomorrow\Chief\Menu\MenuItem::TYPE_INTERNAL)
            <a class="label label--primary" href="{{ $item->url }}" target="_blank">{{ $item->page_label }}</a>
        @elseif($item->type == \Thinktomorrow\Chief\Menu\MenuItem::TYPE_NOLINK)
            -
        @elseif($item->type == \Thinktomorrow\Chief\Menu\MenuItem::TYPE_COLLECTION)

        @else
            <a class="text-subtle" href="{{ $item->url }}" target="_blank">{{ $item->url }}</a>
        @endif
    </div>

    <div class="column-2 text-right">
        @if($item->auto_generated)

        @else
            <a href="{{ route('chief.back.menu.edit', $item->id) }}" class="">Aanpassen</a>
        @endif

    </div>
</div>