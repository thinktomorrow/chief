<div class="relative flex px-8 py-6">
    @if($level != 0)
        <div class="absolute flex flex-col items-end flex-shrink-0 text-grey-700" style="width: {{ $level * 2 }}rem">
            <svg width="18" height="18"><use xlink:href="#icon-arrow-tl-to-br"/></svg>
            <span class="pr-2 text-xs font-semibold">{{ $level }}</span>
        </div>
    @endif

    <div class="flex items-start justify-between w-full space-x-4" style="padding-left: {{ $level * 2 }}rem; {{ $level > 0 ? 'margin-left: 0.75rem;' : null }}">
        <div class="flex flex-wrap items-center">
            <div class="mr-3 space-x-1">
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
                <x-icon-label type="forward" class="mr-3 text-primary-500"></x-icon-label>

                <a class="inline-block label label-primary" href="{{ $item->url }}" target="_blank">
                    {{-- Todo: show internal page title --}}
                    {{ $item->page_label }}
                </a>
            @elseif($item->type == \Thinktomorrow\Chief\Site\Menu\MenuItem::TYPE_NOLINK)
                <x-icon-label type="forward" class="mr-3 text-grey-300"></x-icon-label>

                <span class="font-medium text-grey-300">Geen link</span>
            @else
                <x-icon-label type="forward" class="mr-3 text-primary-500"></x-icon-label>

                <a class="link link-primary" href="{{ $item->url }}" target="_blank">
                    {{ $item->url }}
                </a>
            @endif
        </div>

        <div class="flex items-center flex-shrink-0 cursor-pointer">
            <a
                href="{{ route('chief.back.menuitem.edit', $item->id) }}"
                class="link link-primary"
            >
                <x-icon-label type="edit"></x-icon-label>
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
