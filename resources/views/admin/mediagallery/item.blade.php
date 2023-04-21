@php
    // Account for media files which are not stored on public accessible location. They throw a
    $assetUrl = null;
    try {
        $assetUrl = $asset->url('small');
    } catch(Spatie\MediaLibrary\Exceptions\UrlCannotBeDetermined $e) {}

@endphp

<div>
    <input type="checkbox" id="asset_{{ $index }}" name="asset_ids[]" value="{{ $asset->id }}" class="sr-only peer"/>

    <label
        for="asset_{{ $index }}"
        class="block transition-all duration-75 ease-in-out rounded peer-checked:ring-2 peer-checked:ring-primary-500 peer-checked:ring-offset-[6px]"
    >
        <div class="w-full overflow-hidden aspect-square rounded-xl bg-grey-100">
            @if ($asset->getExtensionType() == "image")
                <img
                    src="{{ $assetUrl }}"
                    alt="{{ $asset->filename() }}"
                    class="object-contain w-full h-full"
                />
            @else
                <div class="flex items-center justify-center w-full h-full text-grey-500">
                    {!! \Thinktomorrow\Chief\Admin\Mediagallery\MimetypeIcon::fromString($asset->getMimetype())->icon() !!}
                </div>
            @endif
        </div>

        <div class="mt-4 space-y-1.5 leading-tight">
            <a
                href="{{ $assetUrl }}"
                title="{{ $asset->filename() }}"
                target="_blank"
                rel="noopener"
                class="text-black"
            >
                {{ $asset->filename() }}
            </a>

            <p class="text-sm text-grey-500">
                {{ $asset->getDimensions() }} | {{ $asset->getSize() }} | {{ $asset->getMimeType() }}
            </p>

            @if (!$asset->isUsed())
                <div class="flex items-start gap-1.5 text-sm text-red-500">
                    <svg class="w-4 h-4 shrink-0"><use xlink:href="#icon-unlinked"/></svg>
                    <span class="-mt-[0.1rem]">Bestand niet in gebruik</span>
                </div>
            @endif
        </div>
    </label>
</div>
