<?php

namespace Thinktomorrow\Chief\Pages\Application;

use Thinktomorrow\Chief\Common\Relations\RelatedCollection;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Models\UniqueSlug;

class UpdatePage
{
    use TranslatableCommand;

    public function handle($id, array $translations, array $relations): Page
    {
        try{
            DB::beginTransaction();

            $page = Page::ignoreCollection()->findOrFail($id);

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

            $this->syncRelations($page, $relations);

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

    private function syncRelations($page, $relateds)
    {
        // First remove all existing children
        foreach($page->children() as $child){
            $page->rejectChild($child);
        }

        foreach(RelatedCollection::inflate($relateds) as $i => $related){
            $page->adoptChild($related, ['sort' => $i]);
        }
    }
}
