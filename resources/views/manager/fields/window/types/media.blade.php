<div class="row-start-start gutter-1">
    @if($model instanceof \Thinktomorrow\AssetLibrary\HasAsset && count($assets = $model->assets($field->getKey())) > 0)
        @foreach ($assets as $asset)
            @php
                // fallback - If the queued conversions haven't run yet,
                // we'll use the original image until they are uploaded
                $url = file_exists($asset->getFirstMediaPath('default', 'thumb')) ? $asset->url('thumb') : $asset->url();
            @endphp

            <div>
                <div class="w-32">
                    @switch($asset->getExtensionType())
                        @case('image')
                            <a href="{{ $asset->url() }}" title="{{ $asset->filename() }}" target="_blank" rel="noopener">
                                <img
                                    src="{{ $url }}"
                                    alt="{{ $asset->filename() }}"
                                    class="w-full h-[4.5rem] rounded-xl object-contain bg-grey-200 bg-gradient-to-br from-grey-100 to-grey-200"
                                >
                            </a>
                            @break
                        @case('pdf')
                            <img
                                src="{{ $url }}"
                                alt="{{ $asset->filename() }}"
                                class="w-full h-[4.5rem] rounded-xl object-contain bg-grey-200 bg-gradient-to-br from-grey-100 to-grey-200"
                            >

                            <a
                                href="{{ $asset->url() }}"
                                title="{{ $asset->filename() }}"
                                target="_blank"
                                rel="noopener"
                                class="mt-1 text-sm link link-primary"
                            >
                                {{ $asset->filename() }} ({{ $asset->getSize() }})
                            </a>

                            @break
                        @default
                            <a
                                href="{{ $asset->url() }}"
                                title="{{ $asset->filename() }}"
                                target="_blank"
                                rel="noopener"
                                class="mt-1 text-sm link link-primary"
                            >
                                {{ $asset->filename() }} ({{ $asset->getSize() }})
                            </a>
                    @endswitch
                </div>
            </div>
        @endforeach
    @else
        <div class="w-32">
            <div class="flex items-center justify-center w-full rounded-xl h-[4.5rem] bg-grey-200 bg-gradient-to-br from-grey-100 to-grey-200">
                <svg width="24" height="24" class="text-grey-400"><use xlink:href="#icon-image" /></svg>
            </div>
        </div>
    @endif
</div>
