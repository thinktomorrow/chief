@php
    $locale = $locale ?? \Thinktomorrow\Chief\Sites\ChiefSites::defaultLocale();

    /** @var \Thinktomorrow\AssetLibrary\Asset[] $assets */
    $assets = $getValue($locale);
    $count = count($assets);
@endphp

<div class="flex flex-wrap -space-x-2">
    @forelse ($assets as $asset)
        <div class="flex gap-4">
            <a href="{{ $asset->getUrl() }}" title="Bestand bekijken" target="_blank" rel="noopener" @class([
                'border-2 border-white rounded-lg' => $count > 1,
            ])>
                <div class="flex items-center justify-center overflow-hidden rounded-lg w-14 h-14 shrink-0 bg-grey-100">
                    @if($asset->isImage())
                        <img
                            src="{{ $asset->getUrl('thumb') }}"
                            alt="{{ $asset->getFileName() }}"
                            class="object-contain w-full h-full"
                        >
                    @elseif($asset instanceof \Thinktomorrow\AssetLibrary\External\ExternalAssetContract)
                        <img
                            src="{{ $asset->getPreviewUrl('thumb') }}"
                            alt="{{ $asset->getFileName() }}"
                            class="object-contain w-full h-full"
                        >
                    @else
                        <svg class="w-6 h-6 text-grey-400">
                            <use xlink:href="#icon-document"/>
                        </svg>
                    @endif
                </div>
            </a>

            @if($count === 1)
                <div class="space-y-0.5 leading-tight py-1.5 grow">
                    <p class="body-dark">
                        {{ $asset->getFileName() }}
                    </p>

                    <p class="text-sm text-grey-500">
                        @if($asset->hasData('external'))
                            {{ ucfirst($asset->getData('external.type')) }}

                            @if($asset->hasData('external.duration'))
                                - {{ $asset->getData('external.duration') }} sec
                            @endif
                        @else
                            {{ $asset->getHumanReadableSize() }} -
                            @if($asset->isImage())
                                {{ $asset->getWidth() }}x{{ $asset->getHeight() }} -
                            @endif
                            {{ strtoupper($asset->getExtension()) }}
                        @endif
                    </p>
                </div>
            @endif
        </div>
    @empty
        <div class="flex items-center justify-center overflow-hidden rounded-lg w-14 h-14 shrink-0 bg-grey-100">
            <svg class="w-6 h-6 text-grey-400">
                <use xlink:href="#icon-document"/>
            </svg>
        </div>
    @endforelse
</div>
