<?php

    // Account for media files which are not stored on public accessible location. They throw a
    $assetUrl = null;
    try {
        $assetUrl = $asset->url();
    } catch(Spatie\MediaLibrary\Exceptions\UrlCannotBeDetermined $e) {}

?>

<label for="asset_{{ $index }}" class="relative block h-full bg-white rounded cursor-pointer formgroup" style="overflow: hidden;">
    <div class="absolute top-0 left-0 m-2 formgroup-input z-1">
        <input type="checkbox" name="asset_ids[]" id="asset_{{ $index }}" value="{{ $asset->id }}" hidden>
        <span class="custom-checkbox" style="pointer-events: auto"></span>
    </div>

    <div class="relative flex items-center justify-center overflow-hidden" style="height: 12rem;">
        @if($asset->getExtensionType() == "image")
            <div class="absolute top-0 bottom-0 left-0 right-0" style="opacity: 0.1; background-position: center; background-size: cover; background-image: url('{{ $assetUrl }}')"></div>
        @endif

        @if($asset->getExtensionType() == "image")
            <img class="relative" src="{{ $assetUrl }}" style="max-width:100%; max-height:100%;">
        @else
            {!! \Thinktomorrow\Chief\Mediagallery\MimetypeIcon::fromString($asset->getMimetype())->icon() !!}
        @endif
    </div>

    <div class="p-3">
        <div class="mb-2">

            <a
                href="{{ $assetUrl }}"
                title="{{ $asset->filename() }}"
                target="_blank"
                class="text-grey-600 hover:underline"
            >
                {{ $asset->filename() }}
            </a>
        </div>

        <div class="flex items-center justify-between">
            @if(!$asset->isUsed())
                <div class="bg-white text-error" title="Dit bestand wordt niet gebruikt op de site.">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 470 470.288" stroke="none"><path d="m323.109375 339.621094c-4.097656 0-8.171875-1.558594-11.308594-4.671875-6.25-6.25-6.269531-16.382813-.019531-22.632813l93.71875-93.910156c20.628906-20.503906 31.976562-47.894531 31.976562-77.183594 0-60.226562-48.875-109.226562-108.949218-109.226562-29.226563 0-56.53125 11.347656-76.925782 31.976562l-93.824218 94.039063c-6.253906 6.269531-16.386719 6.25-22.636719.042969-6.25-6.253907-6.25-16.386719-.019531-22.636719l93.78125-93.972657c26.386718-26.710937 61.78125-41.429687 99.625-41.429687 77.71875 0 140.949218 63.359375 140.949218 141.226563 0 37.886718-14.699218 73.34375-41.386718 99.839843l-93.652344 93.847657c-3.136719 3.132812-7.210938 4.691406-11.328125 4.691406zm0 0"/><path d="m141.09375 470.289062c-77.71875 0-140.949219-63.359374-140.949219-141.226562 0-37.890625 14.699219-73.34375 41.386719-99.863281l56.808594-56.894531c6.230468-6.292969 16.363281-6.273438 22.636718-.042969 6.25 6.25 6.25 16.382812.019532 22.632812l-56.851563 56.984375c-20.652343 20.476563-32 47.871094-32 77.183594 0 60.222656 48.875 109.226562 108.949219 109.226562 29.203125 0 56.511719-11.351562 76.925781-32l56.898438-57.023437c6.230469-6.296875 16.363281-6.273437 22.632812-.042969 6.25 6.25 6.25 16.382813.023438 22.632813l-56.855469 56.980469c-26.386719 26.730468-61.800781 41.453124-99.625 41.453124zm0 0"/><path d="m234.8125 251.152344c-4.097656 0-8.171875-1.558594-11.308594-4.671875-6.25-6.25-6.25-16.382813-.019531-22.636719l85.332031-85.523438c6.230469-6.292968 16.363282-6.25 22.632813-.042968 6.253906 6.25 6.253906 16.382812.023437 22.632812l-85.335937 85.527344c-3.132813 3.15625-7.230469 4.714844-11.324219 4.714844zm0 0"/><path d="m149.476562 336.675781c-4.09375 0-8.167968-1.554687-11.304687-4.671875-6.25-6.25-6.273437-16.382812-.023437-22.632812l47.574218-47.679688c6.230469-6.292968 16.363282-6.25 22.632813-.042968 6.253906 6.25 6.273437 16.382812.023437 22.632812l-47.574218 47.679688c-3.136719 3.136718-7.230469 4.714843-11.328126 4.714843zm0 0"/><path d="m453.476562 470.289062c-4.09375 0-8.191406-1.558593-11.328124-4.714843l-437.332032-438.273438c-6.230468-6.25-6.230468-16.382812.019532-22.632812 6.253906-6.210938 16.386718-6.253907 22.636718.042969l437.332032 438.292968c6.230468 6.25 6.230468 16.382813-.019532 22.632813-3.136718 3.09375-7.210937 4.652343-11.308594 4.652343zm0 0"/></svg>
                </div>
            @endif
            <div class="font-bold">{{ $asset->getDimensions() }}</div>
            <div class="font-bold">{{ $asset->getSize() }}</div>
        </div>
    </div>
</label>
