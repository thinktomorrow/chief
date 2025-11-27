@php
    $locale = $locale ?? \Thinktomorrow\Chief\Sites\ChiefSites::primaryLocale();

    use Thinktomorrow\AssetLibrary\External\ExternalAssetContract;
    use Thinktomorrow\Chief\Assets\App\MimetypeIcon;

    /** @var \Thinktomorrow\AssetLibrary\Asset[] $assets */
    $assets = $getValueOrFallback($locale);

    $count = count($assets);
@endphp

<div class="flex flex-wrap -space-x-2">
    @forelse ($assets as $asset)
        <div class="flex gap-4">
            <a
                href="{{ $asset->getUrl() }}"
                title="Bestand bekijken"
                target="_blank"
                rel="noopener"
                @class([
                    'overflow-hidden rounded-lg border-2 border-white' => $count > 1,
                ])
            >
                <div class="bg-grey-100 flex size-14 shrink-0 items-center justify-center overflow-hidden">
                    @if ($asset->isImage())
                        <img
                            src="{{ $asset->getUrl('thumb') }}"
                            alt="{{ $asset->getFileName() }}"
                            class="size-full object-contain"
                        />
                    @elseif ($asset instanceof ExternalAssetContract)
                        <img
                            src="{{ $asset->getPreviewUrl('thumb') }}"
                            alt="{{ $asset->getFileName() }}"
                            class="size-full object-contain"
                        />
                    @elseif ($asset->getMimeType())
                        <div class="text-grey-400 flex size-full items-center justify-center">
                            <x-dynamic-component
                                :component="MimetypeIcon::fromString($asset->getMimeType())->icon()"
                                class="size-6"
                            />
                        </div>
                    @endif
                </div>
            </a>

            @if ($count === 1)
                <div class="grow space-y-0.5 py-1.5 leading-tight">
                    <p class="body-dark">
                        {{ $asset->getFileName() }}
                    </p>

                    <p class="text-grey-500 text-sm">
                        @if ($asset->hasData('external'))
                            {{ ucfirst($asset->getData('external.type')) }}

                            @if ($asset->hasData('external.duration'))
                                - {{ $asset->getData('external.duration') }} sec
                            @endif
                        @else
                            {{ $asset->getHumanReadableSize() }} -
                            @if ($asset->isImage())
                                    {{ $asset->getWidth() }}x{{ $asset->getHeight() }} -
                            @endif

                            {{ strtoupper($asset->getExtension()) }}
                        @endif
                    </p>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-grey-100 flex size-14 shrink-0 items-center justify-center rounded-lg">
            <x-chief::icon.attachment class="text-grey-400 size-6" />
        </div>
    @endforelse
</div>
