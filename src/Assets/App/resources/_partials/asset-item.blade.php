@php
    $active = $active ?? false;
    $disabled = $disabled ?? false;
    $withActions = $withActions ?? false;
@endphp

<div class="space-y-3">
    <div @class([
        'relative group overflow-hidden aspect-square rounded-lg bg-grey-100 p-[1px]',
        'hover:ring-inset hover:ring-1 hover:ring-primary-500 cursor-pointer' => !$disabled,
        'ring-inset ring-1 ring-primary-500 shadow-md' => $active,
    ])>
        @if ($asset->isImage())
            <img
                src="{{ $asset->getUrl('thumb') }}"
                alt="{{ $asset->getFileName() }}"
                class="object-contain w-full h-full rounded-lg"
            />
        @elseif($asset instanceof \Thinktomorrow\AssetLibrary\External\ExternalAssetContract)
            <div class="relative flex items-center justify-center w-full h-full text-grey-500">

                <div class="absolute w-full left-0 bottom-0 flex justify-center items-center p-1">
                    <span class="label label-info text-xs">{{ $asset->getData('external.type') }}</span>
                </div>

                <img
                    src="{{ $asset->getPreviewUrl('thumb') }}"
                    alt="{{ $asset->getFileName() }}"
                    class="object-contain w-full h-full rounded-lg"
                />
            </div>

        @elseif($asset->getMimeType())
            <div class="flex items-center justify-center w-full h-full text-grey-500">
                {!! \Thinktomorrow\Chief\Admin\Mediagallery\MimetypeIcon::fromString($asset->getMimeType())->icon() !!}
            </div>
        @endif

        @if($disabled)
            <div class="absolute inset-0 flex items-center justify-center gap-1.5 pointer-events-none bg-black/25">
                <span class="bg-black/50 p-1 rounded text-sm text-white">Toegevoegd</span>
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

                @if($asset instanceof \Thinktomorrow\AssetLibrary\External\ExternalAssetContract)
                    <a href="{{ $asset->getUrl() }}" target="_blank" title="View on platform" class="pointer-events-auto">
                        <x-chief::icon-button icon="icon-external-link" />
                    </a>
                @else
                    <a href="{{ $asset->getUrl() }}" target="_blank" title="Download" download class="pointer-events-auto">
                        <x-chief::icon-button icon="icon-download" color="grey"/>
                    </a>
                @endif
            </div>
        @endif
    </div>

    <div class="space-y-0.5">
        <p class="overflow-hidden text-sm body body-dark text-ellipsis whitespace-nowrap">
            {{ $asset->getFileName() }}
        </p>

        <div class="flex justify-between">
            <p class="text-xs body text-grey-500">
                @if($asset->getHumanReadableSize())
                    {{ $asset->getHumanReadableSize() }}
                @elseif($asset->isVideo() && $asset->getData('external.duration'))
                    {{ $asset->getData('external.duration') }} secs.
                @endif
            </p>

            <p class="text-xs uppercase body text-grey-500">
            @if($asset->getWidth())
                {{ $asset->getWidth() }} x {{ $asset->getHeight() }}
            @else
                {{ $asset->getExtension() }}
            @endif
            </p>
        </div>
    </div>
</div>
