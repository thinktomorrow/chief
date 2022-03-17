@php
    /** @var \Thinktomorrow\AssetLibrary\Asset[] $files */
    $files = $getValue($locale);
@endphp

<div class="row-start-start gutter-1">
    @if(count($files) > 0)
        @foreach ($files as $file)
            <div>
                <div class="w-32">
                    @switch($file->extension)
                        @case('image')
                            <a href="{{ $file->url }}" title="{{ $file->filename }}" target="_blank" rel="noopener">
                                <img
                                    src="{{ $file->thumbUrl }}"
                                    alt="{{ $file->filename }}"
                                    class="w-full h-[4.5rem] rounded-xl object-contain bg-grey-200 bg-gradient-to-br from-grey-100 to-grey-200"
                                >
                            </a>
                            @break
                        @case('pdf')
                            <img
                                src="{{ $file->url }}"
                                alt="{{ $file->filename }}"
                                class="w-full h-[4.5rem] rounded-xl object-contain bg-grey-200 bg-gradient-to-br from-grey-100 to-grey-200"
                            >

                            <a
                                href="{{ $file->url }}"
                                title="{{ $file->filename }}"
                                target="_blank"
                                rel="noopener"
                                class="mt-1 text-sm link link-primary"
                            >
                                {{ $file->filename }} ({{ $file->size }})
                            </a>
                            @break
                        @default
                            <a
                                href="{{ $file->url }}"
                                title="{{ $file->filename }}"
                                target="_blank"
                                rel="noopener"
                                class="mt-1 text-sm link link-primary"
                            >
                                {{ $file->filename }} ({{ $file->size }})
                            </a>
                    @endswitch
                </div>
            </div>
        @endforeach
    @else
        <div class="w-32">
            <div class="flex items-center justify-center w-full rounded-xl h-[4.5rem] bg-grey-100">
                <svg width="24" height="24" class="text-grey-400"><use xlink:href="#icon-image" /></svg>
            </div>
        </div>
    @endif
</div>
