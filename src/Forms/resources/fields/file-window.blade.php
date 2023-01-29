@php
    /** @var \Thinktomorrow\AssetLibrary\Asset[] $files */
    $files = $getValue($locale);
    $count = count($files);
@endphp

<div class="flex flex-wrap gap-4">
    @if($count > 0)
        @foreach ($files as $file)
            <div class="flex items-center gap-4">
                <a href="{{ $file->url }}" title="Document bekijken" target="_blank" rel="noopener" class="block">
                    <div class="flex items-center justify-center w-32 h-20 rounded-lg bg-grey-100">
                        <svg width="24" height="24" class="text-grey-400"><use xlink:href="#icon-paper-clip" /></svg>
                    </div>
                </a>

                @if($count === 1)
                    <div class="space-y-0.5 text-sm">
                        <div>
                            <p>
                                <span class="font-medium h6 body-dark">Bestandsnaam:</span>
                                <span class="body body-dark" style="word-break: break-all;">
                                    {{ $file->filename }}
                                </span>
                            </p>

                            <p>
                                <span class="font-medium h6 body-dark">Bestandsgrootte:</span>
                                <span class="body body-dark">{{ $file->size }} </span>
                            </p>
                        </div>

                        <a href="{{ $file->url }}" title="Document bekijken" target="_blank" rel="noopener" class="block">
                            <x-chief-icon-label
                                icon="icon-external-link"
                                position="append"
                                size="18"
                                class="link link-primary"
                            >
                                Document bekijken
                            </x-chief-icon-label>
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="flex items-center justify-center w-32 h-20 rounded-lg bg-grey-100">
            <svg width="24" height="24" class="text-grey-400"><use xlink:href="#icon-paper-clip" /></svg>
        </div>
    @endif
</div>
