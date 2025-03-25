<div class="flex w-full items-start justify-between gap-4">
    <div class="flex flex-wrap items-center gap-3">
        <div class="space-x-1">
            <a href="{{ route('chief.back.menuitem.edit', $item->getNodeId()) }}">
                <span class="font-medium leading-normal text-grey-700">{{ $item->getLabel() }}</span>
            </a>

            @if ($item->isOffline())
                <span class="text-grey-500">(Offline)</span>
            @endif
        </div>

        <x-chief::icon.arrow-right class="size-6 shrink-0 text-grey-400" />

        <a class="label label-primary" href="{{ $item->getUrl() }}" target="_blank">
            {{ $item->getOwnerLabel() }}
        </a>
    </div>

    <x-chief::button
        href="{{ route('chief.back.menuitem.edit', $item->getNodeId()) }}"
        title="Aanpassen"
        size="sm"
        variant="grey"
    >
        <x-chief::icon.quill-write />
    </x-chief::button>
</div>
