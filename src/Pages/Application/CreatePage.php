<?php

namespace Thinktomorrow\Chief\Pages\Application;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Pages\PageTranslation;
use Thinktomorrow\Chief\Common\UniqueSlug;

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
                $translation['slug']    = UniqueSlug::make(new PageTranslation)->get($translation['title'], $page->getTranslation($locale));
                $translations[$locale]  = $translation;
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