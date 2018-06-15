<?php

namespace Thinktomorrow\Chief\Pages\Application;

use Thinktomorrow\Chief\Media\UploadMedia;
use Thinktomorrow\Chief\Common\Relations\RelatedCollection;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Pages\PageTranslation;
use Thinktomorrow\Chief\Common\UniqueSlug;

class CreatePage
{
    use TranslatableCommand;

    public function handle(string $collection, array $translations, array $relations, array $files, array $files_order): Page
    {
        try {
            DB::beginTransaction();

            $page = Page::create(['collection' => $collection]);

            foreach ($translations as $locale => $value) {
                $value = $this->enforceUniqueSlug($value, $page, $locale);

                $page->updateTranslation($locale, $value);
            }

            $this->syncRelations($page, $relations);

            app(UploadMedia::class)->fromUploadComponent($page, $files, $files_order);

            DB::commit();

            return $page->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param array $translations
     * @param $page
     * @return array
     */
    private function enforceUniqueSlug(array $translation, $page, $locale): array
    {
        $translation['slug']    = $translation['slug'] ?? $translation['title'];
        $translation['slug']    = UniqueSlug::make(new PageTranslation)->get($translation['slug'], $page->getTranslation($locale));

        return $translation;
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
