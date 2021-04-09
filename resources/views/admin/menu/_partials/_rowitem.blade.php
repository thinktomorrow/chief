<div class="px-12 py-6">
    <div class="flex justify-between items-start" style="padding-left: {{ $level * 2 }}rem">
        <div>
            <div>
                <a href="{{ route('chief.back.menuitem.edit', $item->id) }}" class="color-inherit">{{ $item->label }}</a>

                {{-- TODO: what is this? --}}
                @if($item->hidden_in_menu)
                    &nbsp;<span class="text-subtle"><em>[ONLINE MAAR VERBORGEN IN MENU]</em></span>
                @elseif($item->draft)
                    &nbsp;<span class="text-subtle"><em>[OFFLINE]</em></span>
                @endif
            </div>

            <div>
                @if($item->type == \Thinktomorrow\Chief\Site\Menu\MenuItem::TYPE_INTERNAL)
                    <a class="label label-primary" href="{{ $item->url }}" target="_blank">{{ $item->page_label }}</a>
                @elseif($item->type == \Thinktomorrow\Chief\Site\Menu\MenuItem::TYPE_NOLINK)
                    -
                @else
                    <a class="link link-primary" href="{{ $item->url }}" target="_blank">{{ $item->url }}</a>
                @endif
            </div>
        </div>

        <div class="flex-shrink-0 flex items-center cursor-pointer">
            <a
                data-sidebar-fragments-edit
                href="{{ route('chief.back.menuitem.edit', $item->id) }}"
                class="link link-black"
            >
                <x-link-label type="edit"></x-link-label>
            </a>
        </div>
    </div>
</div>

@foreach($item->children as $subItem)
    @include('chief::admin.menu._partials._rowitem', [
        'item' => $subItem,
        'level' => $level + 1
    ])
@endforeach
