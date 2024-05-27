@php
    use Thinktomorrow\AssetLibrary\Asset;
    use Thinktomorrow\Chief\Sites\ChiefSites;

    /** @var Asset[] $files */
    $files = $getValue($locale ?? ChiefSites::getDefaultLocale());
    $count = count($files);
@endphp

<div class="flex flex-wrap -space-x-2">
    @forelse ($files as $file)
        <div class="flex gap-4">
            <a href="{{ $file->getUrl() }}" title="Bestand bekijken" target="_blank" rel="noopener" @class([
                'border-2 border-white rounded-lg' => $count > 1,
            ])>
                <div class="flex items-center justify-center overflow-hidden rounded-lg w-14 h-14 shrink-0 bg-grey-100">
                    @if($file->isImage())
                        {{-- TODO: need previewUrl here --}}
                        <img
                                src="{{ $file->getUrl('thumb') }}"
                                alt="{{ $file->getFileName() }}"
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
                        {{ $file->getFileName() }}
                    </p>

                    <p class="text-sm text-grey-500">
                        {{-- TODO: $file->isExternalAsset always return null --}}
                        @if($file->isExternalAsset)
                            {{ ucfirst($file->getExternalAssetType()) }} -
                            {{ $file->getData('external.duration') }} sec
                        @else
                            {{ $file->humanReadableSize }} -
                            @if($file->isImage())
                                {{ $file->width }}x{{ $file->height }} -
                            @endif
                            {{ strtoupper($file->extension) }}
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
