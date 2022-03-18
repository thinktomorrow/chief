@php
    /** @var \Thinktomorrow\AssetLibrary\Asset[] $images */
    $images = $getValue($locale);
    $count = count($images);
@endphp

<div class="flex flex-wrap gap-4 mt-1">
    @if($count > 0)
        @foreach ($images as $image)
            <div class="flex items-center gap-4">
                <div class="w-32 h-20">
                    <a href="{{ $image->url }}" title="{{ $image->filename }}" target="_blank" rel="noopener">
                        <img
                            src="{{ $image->thumbUrl }}"
                            alt="{{ $image->filename }}"
                            class="object-contain w-full h-full rounded-lg bg-grey-100"
                        >
                    </a>
                </div>

                @if($count === 1)
                    <div class="space-y-0.5 text-sm">
                        <div>
                            <p>
                                <span class="font-medium display-base body-dark">Bestandsnaam:</span>
                                <span class="body-base body-dark" style="word-break: break-all;">
                                    {{ $image->filename }}
                                </span>
                            </p>

                            <p>
                                <span class="font-medium display-base body-dark">Bestandsgrootte:</span>
                                <span class="body-base body-dark">{{ $image->size }} </span>
                            </p>
                        </div>

                        <a href="{{ $image->url }}" title="Afbeelding bekijken" target="_blank" rel="noopener" class="block">
                            <x-chief-icon-label
                                icon="icon-external-link"
                                position="append"
                                size="18"
                                class="link link-primary"
                            >
                                Afbeelding bekijken
                            </x-chief-icon-label>
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="flex items-center justify-center w-32 h-20 rounded-lg bg-grey-100">
            <svg width="24" height="24" class="text-grey-400"><use xlink:href="#icon-image" /></svg>
        </div>
    @endif
</div>
