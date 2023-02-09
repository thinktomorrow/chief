<?php

    // Account for media files which are not stored on public accessible location. They throw a
    $assetUrl = null;
    try {
        $assetUrl = $asset->url();
    } catch(Spatie\MediaLibrary\Exceptions\UrlCannotBeDetermined $e) {}

?>

<label for="asset_{{ $index }}" class="relative block overflow-hidden rounded-lg cursor-pointer group hover:bg-grey-100">
    <div class="absolute top-0 left-0 m-2 z-[1]">
        <x-chief::input.checkbox id="asset_{{ $index }}" name="asset_ids[]" value="{{ $asset->id }}"/>
    </div>

    <div class="flex items-center justify-center overflow-hidden rounded-lg group-hover:bg-grey-100 bg-grey-50">
        @if($asset->getExtensionType() == "image")
            <img class="object-contain h-48" src="{{ $assetUrl }}">
        @else
            {!! \Thinktomorrow\Chief\Admin\Mediagallery\MimetypeIcon::fromString($asset->getMimetype())->icon() !!}
        @endif
    </div>

    <div class="p-3">
        <a
            href="{{ $assetUrl }}"
            title="{{ $asset->filename() }}"
            target="_blank"
            rel="noopener"
            class="overflow-hidden font-medium h1-dark whitespace-nowrap overflow-ellipsis hover:underline"
        >
            {{ $asset->filename() }}
        </a>

        <div class="mt-1 text-sm font-medium text-grey-500">
            <p>{{ $asset->getDimensions() }}</p>
            <p>{{ $asset->getSize() }}</p>
        </div>

        @if(!$asset->isUsed())
            <div class="flex items-start gap-1.5 text-xs text-red-500 mt-2">
                <svg width="14" height="14" class="shrink-0"><use xlink:href="#icon-unlinked"/></svg>
                <span>Bestand niet in gebruik</span>
            </div>
        @endif
    </div>
</label>
