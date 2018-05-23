<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Thinktomorrow\AssetLibrary\Models\Asset;
use Chief\Pages\Page;

class MediaLibraryController
{
    public function library()
    {
        $library = Asset::getAllAssets();

        $library = new LengthAwarePaginator(
            $library->forPage(Paginator::resolveCurrentPage(), 8),
            count($library),
            8,
            Paginator::resolveCurrentPage(),
            [
                'path' => Paginator::resolveCurrentPath(),
            ]);
        return view('back.media', compact('library'));
    }

    public function uploadtest()
    {
        $page = Page::first();
        return view('back.uploadtest', compact('page'));
    }

    public function mediaModal()
    {
        $library = Asset::getAllAssets();
        return view('back.media-modal', compact('library'))->render();
    }
}
