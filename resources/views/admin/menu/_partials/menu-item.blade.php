<div class="flex items-start justify-between w-full space-x-4">
    <div class="flex flex-wrap items-center">
        <div class="mr-3 space-x-1">
            <a href="{{ route('chief.back.menuitem.edit', $item->getId()) }}">
                <span class="font-medium leading-normal text-grey-700">{{ $item->getLabel() }}</span>
            </a>

            {{-- TODO: what is this? --}}
            @if($item->isHiddenInMenu())
                <span class="text-grey-500">(Online, maar verborgen in het menu)</span>
            @elseif($item->isDraft())
                <span class="text-grey-500">(Offline)</span>
            @endif
        </div>

        @if($item->getType() == \Thinktomorrow\Chief\Site\Menu\MenuItem::TYPE_INTERNAL)
            <x-chief-icon-label type="forward" class="mr-3 text-primary-500"></x-chief-icon-label>

            <a class="label label-primary" href="{{ $item->getUrl() }}" target="_blank">
                {{ $item->getPageLabel() }}
            </a>
        @elseif($item->getType() == \Thinktomorrow\Chief\Site\Menu\MenuItem::TYPE_NOLINK)
            <x-chief-icon-label type="forward" class="mr-3 text-grey-300"></x-chief-icon-label>

            <span class="font-medium text-grey-300">Geen link</span>
        @else
            <x-chief-icon-label type="forward" class="mr-3 text-primary-500"></x-chief-icon-label>

            <a class="link link-primary" href="{{ $item->getUrl() }}" target="_blank">
                {{ $item->getUrl() }}
            </a>
        @endif
    </div>

    <div data-sortable-ignore class="flex items-center flex-shrink-0 cursor-pointer mt-0.5">
        <a
            href="{{ route('chief.back.menuitem.edit', $item->getId()) }}"
            class="link link-primary"
        >
            <x-chief-icon-label type="edit"></x-chief-icon-label>
        </a>
    </div>
</div>
