<div>
    <input type="checkbox" id="asset_{{ $asset->id }}" name="asset_ids[]" value="{{ $asset->id }}" class="sr-only peer"/>

    <label
        for="asset_{{ $asset->id }}"
        class="block transition-all duration-75 ease-in-out rounded peer-checked:ring-2 peer-checked:ring-primary-500 peer-checked:ring-offset-[6px]"
    >
        <div class="w-full overflow-hidden aspect-square rounded-xl bg-grey-100">
            @if ($asset->isImage())
                <img
                    src="{{ $asset->getUrl('thumb') }}"
                    alt="{{ $asset->getFileName() }}"
                    class="object-contain w-full h-full"
                />
            @elseif($asset->getMimeType())
                <div class="flex items-center justify-center w-full h-full text-grey-500">
                    {!! \Thinktomorrow\Chief\Admin\Mediagallery\MimetypeIcon::fromString($asset->getMimetype())->icon() !!}
                </div>
            @endif
        </div>

        <div class="mt-4 space-y-1.5 leading-tight">
            <a
                href="{{ $asset->getUrl() }}"
                title="{{ $asset->getFileName() }}"
                target="_blank"
                rel="noopener"
                class="text-black"
            >
                {{ $asset->getFileName() }}
            </a>

            <p class="text-sm text-grey-500">
                {{ $asset->getSize() }} | {{ $asset->getMimeType() }}
            </p>
        </div>

        <button wire:click="openAssetEdit('{{ $asset->id }}')" type="button" class="focus:ring-1 rounded-xl focus:ring-primary-500">
            <x-chief::icon-button icon="icon-edit" color="grey" />
        </button>

        <button wire:click="deleteAsset('{{ $asset->id }}')" type="button" class="focus:ring-1 rounded-xl focus:ring-primary-500">
            <x-chief::icon-button icon="icon-trash" color="grey" />
        </button>

        <a
            download
            href="{{ $asset->getUrl() }}"
            title="download"
            class="shrink-0 link link-primary"
        >
            <x-chief::icon-button icon="icon-download" color="grey"/>
        </a>

    </label>
</div>
