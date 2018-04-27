<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Chief\Articles\Application\CreateArticle;
use Chief\Articles\Article;
use Chief\Articles\ArticleRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Thinktomorrow\AssetLibrary\Models\Asset;
use App\Http\Requests\ArticleCreateRequest;
use Chief\Articles\Application\UpdateArticle;

class ArticlesController extends Controller
{
    public function index()
    {
        $published  = Article::where('published', 1)->paginate(10);
        $drafts     = Article::where('published', 0)->get();

        return view('back.articles.index', compact('published', 'drafts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('back.articles.create',['article' => new Article()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(ArticleCreateRequest $request)
    {
        $article = app(CreateArticle::class)->handle($request->trans);

        return redirect()->route('back.articles.index')->with('messages.success', $article->title .' is aangemaakt');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $article    = Article::findOrFail($id);

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
    public function update(Request $request, $id)
    {
        $article = app(UpdateArticle::class)->handle($id, $request->trans);

        return redirect()->route('back.articles.index')->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "'.$article->title .'" werd aangepast');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        $article->delete();
        $message = 'Het item werd verwijderd.';

        return redirect()->route('back.articles.index')->with('messages.warning', $message);
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

//    public function upload($id, Request $request)
//    {
//        $article = Article::find($id);
//        $article->addFile($request->file('image'), $request->type, $request->get('locale'));
//
//        return redirect()->back();
//    }
}