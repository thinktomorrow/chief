<?php

namespace Thinktomorrow\Chief\Admin\Mediagallery\Http;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Urls\UrlHelper;

class MediagalleryController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search', false);
        $unused = $request->input('unused', false);
        $owner = $request->input('owner', false);

        $assets = Asset::with('media')->orderBy('created_at', 'DESC')->select('assets.*');

        if ($search) {
            $assets->whereHas('media', function (Builder $query) use ($search) {
                $query->where('file_name', 'LIKE', '%' . $search . '%');
            });
        }

        if ($unused) {
            $assets->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('asset_pivots')
                    ->whereRaw('asset_pivots.asset_id = assets.id')
                    ->where('asset_pivots.unused', 0);
            });
        }

        if ($owner) {
            $owner = ModelReference::fromString($owner)->instance();
            $modelAssets = collect();
            $modelAssets = $modelAssets->merge($owner->assets());

            $owner->children()->each(function ($module) use ($modelAssets) {
                $modelAssets->merge($module->assets());
            });

            $assets->whereIn('id', $modelAssets->pluck('id'));
        }

        $assets = $assets->paginate(20);

        $pages = UrlHelper::allOnlineModels();

        return view('chief::admin.mediagallery.index', compact('assets', 'pages'));
    }
}
