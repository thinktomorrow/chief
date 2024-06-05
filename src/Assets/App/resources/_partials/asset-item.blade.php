@php
    use Thinktomorrow\AssetLibrary\External\ExternalAssetContract;use Thinktomorrow\Chief\Assets\App\MimetypeIcon;
    $active = $active ?? false;
    $disabled = $disabled ?? false;
    $withActions = $withActions ?? false;
@endphp

<div class="space-y-3">
    <div @class([
        'relative group overflow-hidden aspect-square rounded-lg bg-grey-100 p-[1px]',
        'hover:ring-inset hover:ring-1 cursor-pointer' => !$disabled,
        'hover:ring-grey-400' => !$active,
        'ring-inset ring-1 ring-primary-500 shadow-md' => $active,
    ])>
        @if ($asset->isImage())
            <img
                src="{{ $asset->getUrl('thumb') }}"
                alt="{{ $asset->getFileName() }}"
                class="object-contain w-full h-full rounded-lg"
            />
        @elseif($asset instanceof ExternalAssetContract)
            <img
                src="{{ $asset->getPreviewUrl('thumb') }}"
                alt="{{ $asset->getFileName() }}"
                class="object-contain w-full h-full rounded-lg"
            />

            <div class="absolute bottom-0 left-0 right-0 flex items-center justify-center p-1">
                <span class="label label-xs label-grey">{{ ucfirst($asset->getData('external.type')) }}</span>
            </div>
        @elseif($asset->getMimeType())
            <div class="flex items-center justify-center w-full h-full text-grey-400">
                {!! MimetypeIcon::fromString($asset->getMimeType())->icon() !!}
            </div>
        @endif

        @if($withActions)
            <div
                class="absolute inset-0 items-center justify-center hidden gap-1.5 group-hover:flex pointer-events-none bg-black/25 p-1 flex-wrap">
                <button
                    type="button"
                    aria-label="Bewerk bestand"
                    wire:click="openAssetEdit('{{ $asset->id }}')"
                    class="pointer-events-auto"
                >
                    <x-chief::button>
                        <svg>
                            <use xlink:href="#icon-edit"></use>
                        </svg>
                    </x-chief::button>
                </button>

                <button
                    type="button"
                    aria-label="Verwijder bestand"
                    wire:click="deleteAsset('{{ $asset->id }}')"
                    class="pointer-events-auto"
                >
                    <x-chief::button>
                        <svg>
                            <use xlink:href="#icon-trash"></use>
                        </svg>
                    </x-chief::button>
                </button>

                @if($asset instanceof ExternalAssetContract)
                    <a
                        href="{{ $asset->getUrl() }}"
                        title="Bekijk op platform"
                        target="_blank"
                        rel="noopener"
                        class="pointer-events-auto"
                    >
                        <x-chief::button>
                            <svg>
                                <use xlink:href="#icon-external-link"></use>
                            </svg>
                        </x-chief::button>
                    </a>
                @else
                    <a
                        href="{{ $asset->getUrl() }}"
                        title="Download"
                        download
                        class="pointer-events-auto"
                    >
                        <x-chief::button>
                            <svg>
                                <use xlink:href="#icon-download"></use>
                            </svg>
                        </x-chief::button>
                    </a>
                @endif
            </div>
        @endif

        {{-- Shows a label when the asset is already selected --}}
        @if($disabled)
            <div class="absolute inset-0 flex items-center justify-center p-1 pointer-events-none bg-black/50">
                <span class="label label-xs label-grey">Toegevoegd</span>
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
                    {{ $asset->getData('external.duration') }} sec
                @endif
            </p>

            <p class="text-xs body text-grey-500">
                @if($asset->getWidth())
                    {{ $asset->getWidth() }}x{{ $asset->getHeight() }}
                @else
                    {{ $asset->getExtension() }}
                @endif
            </p>
        </div>
    </div>
</div>
