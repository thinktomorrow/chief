<?php

namespace App\Http\Controllers\Back;


use Chief\Models\Article;
use Chief\Models\Asset;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class MediaLibraryController
{
    public function library()
    {
        $library = Asset::getAllMedia();

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
        $article = Article::first();
        return view('back.uploadtest', compact('article'));
    }

    public function mediaModal()
    {
        $library = Asset::getAllMedia();
        return view('back.media-modal', compact('library'))->render();
    }
}
