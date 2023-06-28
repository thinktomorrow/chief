@php
    $active = $active ?? false;
    $withActions = $withActions ?? false;
@endphp

<div class="space-y-3">
    <div @class([
        'relative group overflow-hidden aspect-square rounded-lg bg-grey-100 p-[1px] cursor-pointer',
        'hover:ring-inset hover:ring-1 hover:ring-primary-500',
        'ring-inset ring-1 ring-primary-500 shadow-md' => $active,
    ])>
        @if ($asset->isImage())
            <img
                src="{{ $asset->getUrl('thumb') }}"
                alt="{{ $asset->getFileName() }}"
                class="object-contain w-full h-full rounded-lg"
            />
        @elseif($asset->getMimeType())
            <div class="flex items-center justify-center w-full h-full text-grey-500">
                {!! \Thinktomorrow\Chief\Admin\Mediagallery\MimetypeIcon::fromString($asset->getMimeType())->icon() !!}
            </div>
        @endif

        @if($withActions)
            <div class="absolute inset-0 items-center justify-center hidden gap-1.5 group-hover:flex pointer-events-none bg-black/25">
                <button wire:click="openAssetEdit('{{ $asset->id }}')" type="button" class="pointer-events-auto">
                    <x-chief::icon-button icon="icon-edit" color="grey"/>
                </button>

                <button wire:click="deleteAsset('{{ $asset->id }}')" type="button" class="pointer-events-auto">
                    <x-chief::icon-button icon="icon-trash" color="grey"/>
                </button>

                <a href="{{ $asset->getUrl() }}" title="Download" download class="pointer-events-auto">
                    <x-chief::icon-button icon="icon-download" color="grey"/>
                </a>
            </div>
        @endif
    </div>

    <div class="space-y-0.5">
        <p class="overflow-hidden text-sm body body-dark text-ellipsis whitespace-nowrap">
            {{ $asset->getFileName() }}
        </p>

        <div class="flex justify-between">
            <p class="text-xs body text-grey-500">
                {{ $asset->getHumanReadableSize() }}
            </p>

            <p class="text-xs uppercase body text-grey-500">
            @if($asset->isImage() && $asset->getImageWidth())
                {{ $asset->getImageWidth() }} x {{ $asset->getImageHeight() }}
            @else
                {{ $asset->getExtension() }}
            @endif
            </p>
        </div>
    </div>
</div>
