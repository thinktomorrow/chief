<div class="flex">
    @if($model instanceof \Thinktomorrow\AssetLibrary\HasAsset && count($assets = $model->assets($field->getKey())) > 0)
        @foreach ($assets as $asset)
            @php
                // fallback - If the queued conversions haven't run yet,
                // we'll use the original image until they are uploaded
                $url = file_exists($asset->getFirstMediaPath('default','thumb'))
                    ? $asset->url('thumb')
                    : $asset->url();
            @endphp

            <div class="w-1/2 overflow-hidden rounded">
                @switch($asset->getExtensionType())
                    @case('image')
                        <img src="{{ $url }}" title="{{ $asset->filename() }}">
                        @break
                    {{-- TODO: maybe some nice previews / icons, easy video / audio playback who knows? --}}
                    @case('pdf')
                        <img src="{{ $asset->url('thumb') }}" title="{{ $asset->filename() }}">
                        <a href="{{ $asset->url() }}">{{ $asset->filename() }} ({{ $asset->getSize() }})</a>
                        @break
                    @case('pdf')
                        <img src="{{ $asset->url('thumb') }}" title="{{ $asset->filename() }}">
                        <a href="{{ $asset->url() }}">{{ $asset->filename() }} ({{ $asset->getSize() }})</a>
                        @break
                    @default
                        <a href="{{ $url }}">{{ $asset->filename() }} ({{ $asset->getSize() }})</a>
                @endswitch
            </div>
        @endforeach
    @else
        <p class="text-grey-400">...</p>
    @endif
</div>
