<div class="row hover:bg-grey-50 px-2 {{ isset($level) ? 'indent-'.$level : 'font-bold' }} ">
    <div class="column center-y py-2">

        <a href="{{ route('chief.back.menuitem.edit', $item->id) }}" class="color-inherit">{{ $item->label }}</a>
        @if($item->hidden_in_menu)
            &nbsp;<span class="text-subtle"><em>[ONLINE MAAR VERBORGEN IN MENU]</em></span>
        @elseif($item->draft)
            &nbsp;<span class="text-subtle"><em>[OFFLINE]</em></span>
        @endif
    </div>

    <div class="column-4 center-y">
        @if($item->type == \Thinktomorrow\Chief\Site\Menu\MenuItem::TYPE_INTERNAL)
            <a class="label label-primary" href="{{ $item->url }}" target="_blank">{{ $item->page_label }}</a>
        @elseif($item->type == \Thinktomorrow\Chief\Site\Menu\MenuItem::TYPE_NOLINK)
            -
        @else
            <a class="text-subtle" href="{{ $item->url }}" target="_blank">{{ $item->url }}</a>
        @endif
    </div>

    <div class="column-2 text-right py-2">
        <a href="{{ route('chief.back.menuitem.edit', $item->id) }}" class="">Aanpassen</a>
    </div>
</div>
