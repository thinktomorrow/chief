<?php

namespace Chief\Pages\Application;

use Chief\Pages\Page;
use Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;
use Chief\Models\UniqueSlug;

class UpdatePage
{
    use TranslatableCommand;

    public function handle($id, array $translations): Page
    {
        DB::transaction(function(){

        }, 2);

        try{
            DB::beginTransaction();

            $page = Page::findOrFail($id);

            //Loops over the uploaded assets and attaches them to the model
            // collect($translations)->each(function ($translation, $locale) use ($page) {
            //     if ($trans = $translation['files']) {
            //         collect($trans)->each(function ($asset_id, $type) use ($page, $locale) {
            //             if ($asset_id) {
            //                 $asset = Asset::find($asset_id);
            //                 $page->addFile($asset, $type, $locale);
            //             }
            //         });
            //     }
            // });

            $this->savePageTranslations($page, $translations);

            DB::commit();
            return $page->fresh();
        } catch(\Throwable $e){
            DB::rollBack();
            throw $e;
        }
    }

    private function savePageTranslations(Page $page, $translations)
    {
        $translations = collect($translations)->map(function ($trans, $locale) {
            $trans['slug'] = strip_tags($trans['slug']);

            return $trans;
        });

        $this->saveTranslations($translations, $page, [
            'slug', 'title', 'content', 'seo_title', 'seo_description'
        ]);
    }
}
