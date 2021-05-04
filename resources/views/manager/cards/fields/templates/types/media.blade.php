<div class="flex">
    @if($model instanceof \Thinktomorrow\AssetLibrary\HasAsset && count($assets = $model->assets($field->getKey())) > 0)
        @foreach ($assets as $asset)

            <?php

            // fallback - If the queued conversions haven't run yet,
            // we'll use the original image until they are uploaded
            $url = file_exists($asset->getFirstMediaPath('default','thumb'))
                ? $asset->url('thumb')
                : $asset->url();

            ?>

            <div class="w-1/2 rounded overflow-hidden">
                @switch($asset->getExtensionType())
                    @case('image')
                        <img src="{{ $url }}" title="{{ $asset->filename() }}">
                    @break

                    @case('pdf')
                    <a href="{{ $url }}">{{ $asset->filename() }} ($asset->getSize())</a>
                    @break

                    @default
                    <a href="{{ $url }}">{{ $asset->filename() }} ($asset->getSize())</a>
                @endswitch

            </div>

        @endforeach
    @else
        <p class="text-grey-400">...</p>
    @endif
</div>

