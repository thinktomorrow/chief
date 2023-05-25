<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class MediaGalleryController extends Controller
{
    public function index(Request $request)
    {
        $limit = 9;
        $offset = 0;
        $excluded = [];

        $limit = $request->query()['limit'] ?? $limit;
        $offset = $request->query()['offset'] ?? $offset;
        $search = $request->query()['search'] ?? '';
        $conversion = $request->query()['conversion'] ?? 'full';

        $excluded = isset($request->query()['excluded']) ? explode(",", $request->query()['excluded']) : $excluded;

        $links = Asset::with('media')
            ->orderBy('created_at', 'DESC')
            ->whereNotIn('id', $excluded)
            ->whereHas('media', function (Builder $query) use ($search) {
                $query->where('mime_type', 'LIKE', '%image%');
                $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->map(function ($asset) use ($conversion) {
                return [
                    "id" => $asset->id,
                    "url" => $asset->url($conversion),
                    "filename" => $asset->filename(),
                    "size" => $asset->getSize(),
                ];
            });

        return response()->json($links->all());
    }
}
