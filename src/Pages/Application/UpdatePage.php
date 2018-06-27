<?php

namespace Thinktomorrow\Chief\Pages\Application;

use Thinktomorrow\Chief\Media\UploadMedia;
use Thinktomorrow\Chief\Common\Relations\RelatedCollection;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Models\UniqueSlug;
use Thinktomorrow\Chief\Common\Audit\Audit;

class UpdatePage
{
    use TranslatableCommand;

    public function handle($id, array $translations, array $relations, array $files, array $files_order): Page
    {
        try {
            DB::beginTransaction();

            $page = Page::ignoreCollection()->findOrFail($id);

            $this->savePageTranslations($page, $translations);

            $this->syncRelations($page, $relations);

            app(UploadMedia::class)->fromUploadComponent($page, $files, $files_order);
            
            Audit::activity()
                ->performedOn($page)
                ->log('edited');

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

        $this->saveTranslations($translations, $page, array_merge([
            'slug', 'seo_title', 'seo_description'
        ], array_keys($page::translatableFields())));
    }

    private function syncRelations($page, $relateds)
    {
        // First remove all existing children
        foreach ($page->children() as $child) {
            $page->rejectChild($child);
        }

        foreach (RelatedCollection::inflate($relateds) as $i => $related) {
            $page->adoptChild($related, ['sort' => $i]);
        }
    }
}
