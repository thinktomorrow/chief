<?php

    // Account for media files which are not stored on public accessible location. They throw a
    $assetUrl = null;
    try {
        $assetUrl = $asset->url();
    } catch(Spatie\MediaLibrary\Exceptions\UrlCannotBeDetermined $e) {}

?>

<label for="asset_{{ $index }}" class="relative block h-full overflow-hidden border cursor-pointer bg-grey-50 border-grey-200 rounded-xl">
    <div class="absolute top-0 left-0 m-2" style="z-index: 1;">
        <div class="flex items-center space-x-2 cursor-pointer text-grey-700 with-custom-checkbox">
            <input type="checkbox" name="asset_ids[]" id="asset_{{ $index }}" value="{{ $asset->id }}">
        </div>
    </div>

    <div class="relative flex items-center justify-center overflow-hidden" style="height: 12rem;">
        @if($asset->getExtensionType() == "image")
            <div
                class="absolute top-0 bottom-0 left-0 right-0 bg-center bg-cover opacity-10"
                style="background-image: url('{{ $assetUrl }}')"
            ></div>
        @endif

        @if($asset->getExtensionType() == "image")
            <img class="relative max-w-full max-h-full" src="{{ $assetUrl }}">
        @else
            {!! \Thinktomorrow\Chief\Admin\Mediagallery\MimetypeIcon::fromString($asset->getMimetype())->icon() !!}
        @endif
    </div>

    <div class="p-3 space-y-1">
        <div>
            <a
                href="{{ $assetUrl }}"
                title="{{ $asset->filename() }}"
                target="_blank"
            >
                <p> {{ $asset->filename() }} </p>
            </a>
        </div>

        <div class="flex items-center justify-between">
            @if(!$asset->isUsed())
                <div class="text-red-500" title="Dit bestand wordt niet gebruikt op de site.">
                    <svg width="16" height="16"><use xlink:href="#icon-unlinked"/></svg>
                </div>
            @endif

            <div>
                <p class="font-semibold">{{ $asset->getDimensions() }}</p>
            </div>

            <div>
                <p class="font-semibold">{{ $asset->getSize() }}</p>
            </div>
        </div>
    </div>
</label>
