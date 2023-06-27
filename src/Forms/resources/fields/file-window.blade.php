@php
    /** @var \Thinktomorrow\AssetLibrary\Asset[] $files */
    $files = $getValue($locale);
    $count = count($files);
@endphp

<div class="flex flex-wrap -space-x-2">
    @if($count > 0)
        @foreach ($files as $file)
            <div class="flex gap-4">
                <a href="{{ $file->getUrl() }}" title="Bestand bekijken" target="_blank" rel="noopener" @class([
                    'border-2 border-white rounded-lg' => $count > 1,
                ])>
                    @if($file->isImage())
                        <img
                            src="{{ $file->getUrl('thumb') }}"
                            alt="{{ $file->getFileName() }}"
                            class="object-contain w-16 h-16 rounded-lg bg-grey-100"
                        >
                    @else
                        <div class="flex items-center justify-center w-16 h-16 rounded-lg bg-grey-100">
                            <svg width="24" height="24" class="text-grey-400"><use xlink:href="#icon-paper-clip"/></svg>
                        </div>
                    @endif
                </a>

                @if($count === 1)
                    <div class="flex items-center py-2 grow">
                        <div class="space-y-0.5 leading-tight">
                            <p class="text-black">
                                {{ $file->getFileName() }}
                            </p>

                            <p class="text-sm text-grey-500">
                                {{ $file->getHumanReadableSize() }} - <span class="uppercase">{{ $file->getExtension() }}</span>
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="flex items-center justify-center w-16 h-16 rounded-lg bg-grey-100">
            <svg width="24" height="24" class="text-grey-400"><use xlink:href="#icon-paper-clip"/></svg>
        </div>
    @endif
</div>
