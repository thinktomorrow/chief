<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class MediaGalleryController extends Controller
{
    public function index(Request $request)
    {
        $limit  = 9;
        $offset = 0;

        $limit  = $request->query()['limit'] ?? $limit;
        $offset = $request->query()['offset'] ?? $offset;
        $links = Asset::orderBy('created_at','DESC')->offset($offset)->limit($limit)->get()->map(function ($asset) {
            return [
                "id"         => $asset->id,
                "url"        => $asset->url(),
                "filename"   => $asset->filename(),
                "dimensions" => $asset->getDimensions(),
                "size"       => $asset->getSize(),
            ];
        });

        return response()->json($links->all());
    }
}
