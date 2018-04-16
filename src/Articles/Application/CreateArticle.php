<?php

namespace Chief\Articles\Application;

use Chief\Articles\Article;
use Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;

class CreateArticle
{
    use TranslatableCommand;

    public function handle(array $translations): Article
    {
        DB::transaction(function(){

        }, 2);

        try{
            DB::beginTransaction();

            $article = Article::create();

            $this->saveTranslations($translations, $article, [
                'slug', 'title', 'content', 'seo_title', 'seo_description'
            ]);

            DB::commit();

            return $article->fresh();

        } catch(\Throwable $e){
            DB::rollBack();
            throw $e;
        }


    }
}