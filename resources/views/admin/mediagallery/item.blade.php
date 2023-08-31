@php
    // Account for media files which are not stored on public accessible location. They throw a
    use Thinktomorrow\Chief\Assets\App\MimetypeIcon;$assetUrl = null;
    try {
        $assetUrl = $asset->url('small');
    } catch(Spatie\MediaLibrary\Exceptions\UrlCannotBeDetermined $e) {}

@endphp

<label for="asset_{{ $index }}"
       class="relative block overflow-hidden rounded-lg cursor-pointer group hover:bg-grey-100">
    <div class="absolute top-0 left-0 m-2 z-[1] form-light">
        <x-chief::input.checkbox id="asset_{{ $index }}" name="asset_ids[]" value="{{ $asset->id }}"/>
    </div>

    <div class="flex items-center justify-center overflow-hidden rounded-lg group-hover:bg-grey-100 bg-grey-50">
        @if($asset->getExtensionType() == "image")
            <img class="object-contain h-48" src="{{ $assetUrl }}">
        @else
            {!! MimetypeIcon::fromString($asset->getMimetype())->icon() !!}
        @endif
    </div>

    <div class="p-3">
        <a
            href="{{ $asset->url() }}"
            title="{{ $asset->filename() }}"
            target="_blank"
            rel="noopener"
            class="overflow-hidden font-medium h1-dark whitespace-nowrap overflow-ellipsis hover:underline"
        >
            {{ $asset->filename() }}
        </a>

        <div class="mt-1 text-sm font-medium text-grey-500">
            <p></p>
            <p>{{ $asset->getSize() }}</p>
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
                {{ $asset->getSize() }} | {{ $asset->getMimeType() }}
            </p>

            @if (!$asset->isUsed())
                <div class="flex items-start gap-1.5 text-sm text-red-500">
                    <svg class="w-4 h-4 shrink-0">
                        <use xlink:href="#icon-unlinked"/>
                    </svg>
                    <span class="-mt-[0.1rem]">Bestand niet in gebruik</span>
                </div>
            @endif
        </div>
</label>
</div>
