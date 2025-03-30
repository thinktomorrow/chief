@php
    $locale = $locale ?? \Thinktomorrow\Chief\Sites\Locales\ChiefLocales::primaryLocale();

    use Thinktomorrow\AssetLibrary\External\ExternalAssetContract;
    use Thinktomorrow\Chief\Assets\App\MimetypeIcon;
    /** @var \Thinktomorrow\AssetLibrary\Asset[] $assets */
    $assets = $getValue($locale);
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
                    'rounded-lg border-2 border-white' => $count > 1,
                ])
            >
                <div class="flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-grey-100">
                    @if ($asset->isImage())
                        <img
                            src="{{ $asset->getUrl('thumb') }}"
                            alt="{{ $asset->getFileName() }}"
                            class="h-full w-full object-contain"
                        />
                    @elseif ($asset instanceof ExternalAssetContract)
                        <img
                            src="{{ $asset->getPreviewUrl('thumb') }}"
                            alt="{{ $asset->getFileName() }}"
                            class="h-full w-full object-contain"
                        />
                    @elseif ($asset->getMimeType())
                        <div class="flex h-full w-full items-center justify-center text-grey-400">
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

                    <p class="text-sm text-grey-500">
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
        <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-grey-100">
            <x-chief::icon.attachment class="h-6 w-6 text-grey-400" />
        </div>
    @endforelse
</div>
