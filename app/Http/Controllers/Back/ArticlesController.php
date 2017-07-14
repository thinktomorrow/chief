<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Chief\Models\Article;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function index()
    {
        $articlesMedia = Article::first()->getMedia();

        // TODO: create articles management
        return view('back.articles.index', compact('articlesMedia'));
    }

    public function store(Request $request)
    {
        $article = Article::first();

        $article->addMedia($request->file('image'))->toMediaCollection();

        return redirect()->back();
    }


}