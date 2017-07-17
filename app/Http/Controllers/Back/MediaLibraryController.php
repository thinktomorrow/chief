<?php

namespace App\Http\Controllers\Back;


use Chief\Models\Article;
use Chief\Models\Asset;

class MediaLibraryController
{
    public function library()
    {
        $library = Asset::getAllMedia();
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