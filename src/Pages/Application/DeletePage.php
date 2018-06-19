<?php

namespace Thinktomorrow\Chief\Pages\Application;

use Thinktomorrow\Chief\Media\UploadMedia;
use Thinktomorrow\Chief\Common\Relations\RelatedCollection;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Pages\PageTranslation;
use Thinktomorrow\Chief\Common\UniqueSlug;
use Thinktomorrow\Chief\Common\Audit\Audit;

class DeletePage
{
    use TranslatableCommand;

    public function handle($id)
    {
        try {
            DB::beginTransaction();

            $page = Page::ignoreCollection()->withArchived()->findOrFail($id);

            if (request()->get('deleteconfirmation') !== 'DELETE' && (!$page->isPublished() || $page->isArchived())) {
                return false;
            }

            if ($page->isDraft() || $page->isArchived()) {
                $page->delete();

                Audit::activity()
                    ->performedOn($page)
                    ->log('deleted');
            }
            
            if ($page->isPublished()) {
                $page->archive();

                Audit::activity()
                    ->performedOn($page)
                    ->log('archived');
            }

            DB::commit();

            return $page;
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
