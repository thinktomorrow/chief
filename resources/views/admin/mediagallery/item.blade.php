<label for="asset_{{ $index }}" class="relative block h-full bg-grey-50 border border-grey-150 rounded-xl cursor-pointer overflow-hidden">
    <div class="absolute top-0 left-0 m-2 z-1">
        <div class="flex items-center text-grey-700 space-x-2 cursor-pointer with-custom-checkbox">
            <input type="checkbox" name="asset_ids[]" id="asset_{{ $index }}" value="{{ $asset->id }}">
        </div>
    </div>

    <div class="relative flex items-center justify-center overflow-hidden" style="height: 12rem;">
        @if($asset->getExtensionType() == "image")
            <div class="absolute top-0 bottom-0 left-0 right-0" style="opacity: 0.1; background-position: center; background-size: cover; background-image: url('{{ $asset->url() }}')"></div>
        @endif

        @if($asset->getExtensionType() == "image")
            <img class="relative" src="{{ $asset->url() }}" style="max-width:100%; max-height:100%;">
        @else
            {!! \Thinktomorrow\Chief\Admin\Mediagallery\MimetypeIcon::fromString($asset->getMimetype())->icon() !!}
        @endif
    </div>

    <div class="p-3 space-y-1">
        <div>
            <a
                href="{{ $asset->url() }}"
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
