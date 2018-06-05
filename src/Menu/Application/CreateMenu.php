<?php

namespace Thinktomorrow\Chief\Menu\Application;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Pages\PageTranslation;
use Thinktomorrow\Chief\Common\UniqueSlug;

class CreateMenu
{
    use TranslatableCommand;

    public function handle(Request $request): Page
    {
        try{
            DB::beginTransaction();

            $menu = MenuItem::create(['label:nl' => 'first item']);

            $translations = $this->enforceUniqueSlug($translations, $menu);

            $this->saveTranslations($translations, $menu, [
                'slug', 'title', 'content', 'seo_title', 'seo_description'
            ]);

            DB::commit();

            return $menu->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param array $translations
     * @param $menu
     * @return array
     */
    private function enforceUniqueSlug(array $translations, $menu): array
    {
        foreach ($translations as $locale => $translation) {
            $translation['slug'] = UniqueSlug::make(new PageTranslation)->get($translation['title'], $menu->getTranslation($locale));
            $translations[$locale] = $translation;
        }

        return $translations;
    }
}
