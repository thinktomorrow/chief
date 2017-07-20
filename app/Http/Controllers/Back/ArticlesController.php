<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Chief\Models\Article;
use Chief\Models\ArticleRepository;
use Chief\Models\Asset;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class ArticlesController extends Controller
{
    public function index()
    {
        $articles = Article::sortedByPublished()->sortedByRecent()->paginate(10);

        return view('back.articles.index', compact('articles'));
    }

    public function upload($id, Request $request)
    {
        $article = Article::find($id);
        if($article->hasMedia())
        {
            $article->asset->first()->uploadToAsset($request->file('image'), $request->imageCollection);
        }else{
            $asset = Asset::upload($request->file('image'), $request->imageCollection)->attachToModel($article);
        }

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $article = new Article();
        return view('back.articles.create',compact('article'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $article = (new ArticleRepository($request))->create();

        return redirect()->route('back.article.index')->with('messages.success', $article->title .' werd aangemaakt');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);

        $article->injectTranslationForForm();
        return view('back.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request,$id)
    {
        $article = (new ArticleEditController($request,$id))->edit();

        return redirect()->route('back.article.index')->with('messages.success', '"'.$article->title .'" werd geupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $message = (new ArticleDeleteController($id))->delete();

        return redirect()->route('back.article.index')->with('messages.warning', $message);
    }

    public function publish(Request $request)
    {
        $article = Article::findOrFail($request->get('id'));
        $published = true === !$request->checkboxStatus; // string comp. since bool is passed as string

        ($published) ? $article->publish() : $article->draft();

        return redirect()->back();

//        return response()->json([
//            'message' => $published ? 'nieuwsartikel werd online gezet' : 'nieuwsartikel werd offline gehaald',
//            'published'=> $published,
//            'id'=> $article->id
//        ],200);
    }
}