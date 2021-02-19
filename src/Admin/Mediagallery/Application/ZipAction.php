<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Mediagallery\Application;

use Illuminate\Http\Request;
use Thinktomorrow\AssetLibrary\Asset;
use ZipStream\Option\Archive;
use ZipStream\ZipStream;

final class ZipAction
{
    public function __construct()
    {
    }

    public function handle(string $filename, Request $request)
    {
        $assets = Asset::whereIn('id', (array) $request->input('asset_ids', []))->get();

        // enable output of HTTP headers TODO: this should be moved to the controller instead...
        $options = new Archive();
        $options->setSendHttpHeaders(true);

        $zip = new ZipStream($filename, $options);

        $assets->each(function (Asset $asset) use ($zip) {
            if (file_exists($asset->getFirstMediaPath())) {
                $zip->addFileFromPath($asset->filename(), $asset->getFirstMediaPath());
            }
        });

        // Output the zip as download
        $zip->finish();
    }
}
