<?php

namespace Chief\Articles\Application;

use Chief\Articles\Article;
use Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;
use Chief\Models\UniqueSlug;

class UpdateArticle
{
    use TranslatableCommand;

    public function handle($id, array $translations): Article
    {
        DB::transaction(function(){

        }, 2);

        try{
            DB::beginTransaction();

            $article = Article::findOrFail($id);

            //Loops over the uploaded assets and attaches them to the model
            // collect($translations)->each(function ($translation, $locale) use ($article) {
            //     if ($trans = $translation['files']) {
            //         collect($trans)->each(function ($asset_id, $type) use ($article, $locale) {
            //             if ($asset_id) {
            //                 $asset = Asset::find($asset_id);
            //                 $article->addFile($asset, $type, $locale);
            //             }
            //         });
            //     }
            // });

            $this->saveArticleTranslations($article, $translations);

            DB::commit();
            return $article->fresh();
        } catch(\Throwable $e){
            DB::rollBack();
            throw $e;
        }
    }

    private function saveArticleTranslations(Article $article, $translations)
    {
        $translations = collect($translations)->map(function ($trans, $locale) {
            $trans['slug'] = strip_tags($trans['slug']);

            return $trans;
        });

        $this->saveTranslations($translations, $article, [
            'slug', 'title', 'content', 'seo_title', 'seo_description'
        ]);
    }
}
