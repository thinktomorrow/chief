<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Chief\Models\Article;
use Chief\Models\ArticleRepository;
use Chief\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticlesController extends Controller
{
    public function index()
    {
        $articles = Article::paginate(10);

        return view('back.articles.index', compact('articles'));
    }

    public function upload($id, Request $request)
    {
        $article = Article::find($id);
        $article->addFile($request->file('image'), $request->type, $request->get('locale'));

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
        $assets = Asset::getAllAssets();

        return view('back.articles.create',compact('article', 'assets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $article = (new ArticleRepository())->create($request);

        return redirect()->route('articles.index')->with('messages.success', $article->title .' werd aangemaakt');
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
        $assets = Asset::getAllAssets();

        $article->injectTranslationForForm();
        return view('back.articles.edit', compact('article', 'assets'));
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
        $article = (new ArticleRepository())->edit($request, $id);


        return redirect()->route('articles.index')->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "'.$article->title .'" werd aangepast');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $message = (new ArticleRepository())->remove($id);

        return redirect()->route('back.article.index')->with('messages.warning', $message);
    }

    public function publish(Request $request)
    {
        $article    = Article::findOrFail($request->get('id'));
        $published  = true === !$request->checkboxStatus; // string comp. since bool is passed as string

        ($published) ? $article->publish() : $article->draft();

        return redirect()->back();

//        return response()->json([
//            'message' => $published ? 'nieuwsartikel werd online gezet' : 'nieuwsartikel werd offline gehaald',
//            'published'=> $published,
//            'id'=> $article->id
//        ],200);
    }
}