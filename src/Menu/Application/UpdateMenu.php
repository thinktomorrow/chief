<?php

namespace Thinktomorrow\Chief\Menu\Application;

use Thinktomorrow\Chief\Common\Relations\RelatedCollection;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Models\UniqueSlug;

class UpdateMenu
{
    use TranslatableCommand;

    public function handle($id, array $translations, array $relations): Page
    {
        try {
            DB::beginTransaction();

            $page = Page::ignoreCollection()->findOrFail($id);

            $this->savePageTranslations($page, $translations);

            $this->syncRelations($page, $relations);

            DB::commit();
            return $page->fresh();
        } catch (\Throwable $e) {
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
