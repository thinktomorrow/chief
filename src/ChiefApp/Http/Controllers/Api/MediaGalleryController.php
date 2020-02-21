<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Thinktomorrow\AssetLibrary\Asset;
use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class MediaGalleryController extends Controller
{
    public function index(Request $request)
    {
        $limit  = 9;
        $offset = 0;
        $excluded = [];

        $limit  = $request->query()['limit'] ?? $limit;
        $offset = $request->query()['offset'] ?? $offset;
        $excluded = isset($request->query()['excluded']) ? explode(",", $request->query()['excluded']) : $excluded;

        $links = Asset::with('media')->orderBy('created_at', 'DESC')->whereNotIn('id', $excluded)->whereHas('media', function (Builder $query) {
            $query->where('mime_type', 'LIKE', '%image%');
        })->offset($offset)->limit($limit)->get()->map(function ($asset) {
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
