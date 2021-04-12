<div class="flex px-12 py-6">
    @if($level != 0)
        <div class="flex-shrink-0 flex flex-col items-end text-grey-700 pr-3" style="width: {{ $level * 2 + 0.5 }}rem">
            <svg width="18" height="18"><use xlink:href="#icon-arrow-tl-to-br"/></svg>
            <span class="text-xs pr-2 font-semibold">{{ $level }}</span>
        </div>
    @endif

    <div class="w-full flex justify-between items-start space-x-4">
        <div class="flex flex-wrap items-center">
            <div class="space-x-1 mr-3">
                <a href="{{ route('chief.back.menuitem.edit', $item->id) }}">
                    <span class="font-medium text-grey-700">{{ $item->label }}</span>
                </a>

                {{-- TODO: what is this? --}}
                @if($item->hidden_in_menu)
                    <span class="text-grey-500">(Online, maar verborgen in het menu)</span>
                @elseif($item->draft)
                    <span class="text-grey-500">(Offline)</span>
                @endif
            </div>

            @if($item->type == \Thinktomorrow\Chief\Site\Menu\MenuItem::TYPE_INTERNAL)
                <x-link-label type="forward" class="text-primary-500 mr-3"></x-link-label>

                <a class="label label-primary inline-block" href="{{ $item->url }}" target="_blank">
                    {{-- Todo: show internal page title --}}
                    {{ $item->page_label }}
                </a>
            @elseif($item->type == \Thinktomorrow\Chief\Site\Menu\MenuItem::TYPE_NOLINK)
                {{-- <span class="text-grey-700">/</span> --}}
            @else
                <x-link-label type="forward" class="text-primary-500 mr-3"></x-link-label>

                <a class="link link-primary" href="{{ $item->url }}" target="_blank">
                    {{ $item->url }}
                </a>
            @endif
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
    @include('chief::admin.menu._partials.menu-item', [
        'item' => $subItem,
        'level' => $level + 1
    ])
@endforeach
