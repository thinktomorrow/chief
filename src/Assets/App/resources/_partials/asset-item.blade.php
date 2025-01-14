@php
    use Thinktomorrow\AssetLibrary\External\ExternalAssetContract;
    use Thinktomorrow\Chief\Assets\App\MimetypeIcon;
    $active = $active ?? false;
    $disabled = $disabled ?? false;
    $withActions = $withActions ?? false;
@endphp

<div class="space-y-3">
    <div
        @class([
            'group relative aspect-square overflow-hidden rounded-lg bg-grey-100 p-[1px]',
            'cursor-pointer hover:ring-1 hover:ring-inset' => ! $disabled,
            'hover:ring-grey-400' => ! $active,
            'shadow-md ring-1 ring-inset ring-primary-500' => $active,
        ])
    >
        @if ($asset->isImage())
            <img
                src="{{ $asset->getUrl('thumb') }}"
                alt="{{ $asset->getFileName() }}"
                class="h-full w-full rounded-lg object-contain"
            />
        @elseif ($asset instanceof ExternalAssetContract)
            <img
                src="{{ $asset->getPreviewUrl('thumb') }}"
                alt="{{ $asset->getFileName() }}"
                class="h-full w-full rounded-lg object-contain"
            />

            <div class="absolute bottom-0 left-0 right-0 flex items-center justify-center p-1">
                <span class="label label-xs label-grey">{{ ucfirst($asset->getData('external.type')) }}</span>
            </div>
        @elseif ($asset->getMimeType())
            <div class="flex h-full w-full items-center justify-center text-grey-400">
                {!! MimetypeIcon::fromString($asset->getMimeType())->icon() !!}
            </div>
        @endif

        @if ($withActions)
            <div
                class="pointer-events-none absolute inset-0 hidden flex-wrap items-center justify-center gap-1.5 bg-black/25 p-1 group-hover:flex"
            >
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

                @if ($asset instanceof ExternalAssetContract)
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
                    <a href="{{ $asset->getUrl() }}" title="Download" download class="pointer-events-auto">
                        <x-chief::button>
                            <x-chief::icon.download />
                        </x-chief::button>
                    </a>
                @endif
            </div>
        @endif

        {{-- Shows a label when the asset is already selected --}}
        @if ($disabled)
            <div class="pointer-events-none absolute inset-0 flex items-center justify-center bg-black/50 p-1">
                <span class="label label-xs label-grey">Toegevoegd</span>
            </div>
        @endif
    </div>

    <div class="space-y-0.5">
        <p class="body body-dark overflow-hidden text-ellipsis whitespace-nowrap text-sm">
            {{ $asset->getFileName() }}
        </p>

        <div class="flex justify-between">
            <p class="body text-xs text-grey-500">
                @if ($asset->getHumanReadableSize())
                    {{ $asset->getHumanReadableSize() }}
                @elseif ($asset->isVideo() && $asset->getData('external.duration'))
                    {{ $asset->getData('external.duration') }} sec
                @endif
            </p>

            <p class="body text-xs text-grey-500">
                @if ($asset->getWidth())
                    {{ $asset->getWidth() }}x{{ $asset->getHeight() }}
                @else
                    {{ $asset->getExtension() }}
                @endif
            </p>
        </div>
    </div>
</div>
