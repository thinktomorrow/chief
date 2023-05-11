<div class="flex items-start justify-between w-full space-x-4">
    <div class="flex flex-wrap items-center">
        <div class="mr-3 space-x-1">
            <a href="{{ route('chief.back.menuitem.edit', $item->getId()) }}">
                <span class="font-medium leading-normal text-grey-700">{{ $item->getLabel() }}</span>
            </a>

            @if($item->isOffline())
                <span class="text-grey-500">(Offline)</span>
            @endif
        </div>

        <x-chief::icon-label type="forward" class="mr-3 text-primary-500"></x-chief::icon-label>
        <a class="label label-primary" href="{{ $item->getUrl() }}" target="_blank">
            {{ $item->getAdminUrlLabel() }}
        </a>
    </div>

    <div data-sortable-ignore class="flex items-center shrink-0 cursor-pointer -my-0.5">
        <a
            href="{{ route('chief.back.menuitem.edit', $item->getId()) }}"
            title="Aanpassen"
            class="link link-primary"
        >
            <x-chief::icon-button icon="icon-edit"/>
        </a>
    </div>
</div>
