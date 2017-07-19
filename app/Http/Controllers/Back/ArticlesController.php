<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Chief\Models\Article;
use Chief\Models\Asset;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class ArticlesController extends Controller
{
    use HasMediaTrait;

    public function index()
    {
        $articlesMedia = Article::first();

        // TODO: create articles management
        return view('back.articles.index', compact('articlesMedia'));
    }

    public function store(Request $request)
    {
        $article = Article::first();
        $article->addMedia($request->file('image'))->toMediaLibrary();

        return redirect()->back();
    }

    public function upload($id, Request $request)
    {
        $article = Article::find($id);

        $asset = Asset::upload($request->file('image'))->attachToModel($article);

        return redirect()->back();
    }

}