<?php

namespace Chief\Pages\Application;

use Chief\Pages\Page;
use Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;

class CreatePage
{
    use TranslatableCommand;

    public function handle(array $translations): Page
    {
        DB::transaction(function(){

        }, 2);

        try{
            DB::beginTransaction();

            $page = Page::create();

            foreach ($translations as $locale => $translation) {
                $translation['slug'] = strip_tags($translation['slug']);
                $translations[$locale] = $translation;
            }

            $this->saveTranslations($translations, $page, [
                'slug', 'title', 'content', 'seo_title', 'seo_description'
            ]);

            DB::commit();

            return $page->fresh();

        } catch(\Throwable $e){
            DB::rollBack();
            throw $e;
        }
    }
}