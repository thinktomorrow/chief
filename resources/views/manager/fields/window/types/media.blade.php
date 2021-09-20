<div class="row-start-start gutter-1">
    @if($model instanceof \Thinktomorrow\AssetLibrary\HasAsset && count($assets = $model->assets($field->getKey())) > 0)
        @foreach ($assets as $asset)
            @php
                // fallback - If the queued conversions haven't run yet,
                // we'll use the original image until they are uploaded
                $url = file_exists($asset->getFirstMediaPath('default', 'thumb'))
                    ? $asset->url('thumb')
                    : $asset->url();
            @endphp

            <div>
                <div class="flex items-center justify-center w-20 h-20 overflow-hidden rounded-lg bg-grey-100">
                    @switch($asset->getExtensionType())
                        @case('image')
                            <a href="{{ $asset->url() }}" title="{{ $asset->filename() }}" target="_blank">
                                <img src="{{ $url }}" alt="{{ $asset->filename() }}">
                            </a>
                            @break
                        {{-- TODO: maybe some nice previews / icons, easy video / audio playback who knows? --}}
                        @case('pdf')
                            <img src="{{ $url }}" alt="{{ $asset->filename() }}">

                            <a href="{{ $asset->url() }}" title="{{ $asset->filename() }}" target="_blank" class="link link-primary">
                                {{ $asset->filename() }} ({{ $asset->getSize() }})
                            </a>
                            @break
                        @default
                            <a href="{{ $asset->url() }}" title="{{ $asset->filename() }}" target="_blank" class="link link-primary">
                                {{ $asset->filename() }} ({{ $asset->getSize() }})
                            </a>
                    @endswitch
                </div>
            </div>
        @endforeach
    @else
        <p class="text-grey-400">...</p>
    @endif
</div>
