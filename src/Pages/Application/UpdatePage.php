<?php

namespace Thinktomorrow\Chief\Pages\Application;

use Thinktomorrow\Chief\Common\FlatReferences\FlatReferenceCollection;
use Thinktomorrow\Chief\Media\UploadMedia;
use Thinktomorrow\Chief\PageBuilder\UpdateSections;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Models\UniqueSlug;
use Thinktomorrow\Chief\Common\Audit\Audit;
use Illuminate\Support\Carbon;

class UpdatePage
{
    use TranslatableCommand;

    public function handle($id, array $sections, array $translations, array $custom_fields, array $relations, array $files, array $files_order): Page
    {
        try {
            DB::beginTransaction();

            $page           = Page::findOrFail($id);

            $this->savePageTranslations($page, $translations);

            $this->saveSections($page, $sections);

            $this->saveCustomFields($page, $custom_fields);

            // Explicit relations - these are the related modules/pages passed outside the pagebuilder
            // This is currently not being used as the pagebuilder already takes care of this.
            // This is disabled because the nature of sync will remove all none present children.
            // $this->syncRelations($page, $relations);

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
            if ($trans['slug'] != '') {
                $trans['slug'] = str_slug($trans['slug']);
            } else {
                $trans['slug'] = str_slug($trans['title']);
            }

            return $trans;
        });

        $this->saveTranslations($translations, $page, array_merge([
            'title', 'slug', 'seo_title', 'seo_description'
        ], array_keys($page::translatableFields())));
    }

    private function syncRelations($page, $relateds)
    {
        // First remove all existing children
        foreach ($page->children() as $child) {
            $page->rejectChild($child);
        }

        foreach (FlatReferenceCollection::fromFlatReferences($relateds) as $i => $related) {
            $page->adoptChild($related, ['sort' => $i]);
        }
    }

    private function saveSections($page, $sections)
    {
        $modules = $sections['modules'] ?? [];
        $text    = $sections['text'] ?? [];
        $order   = $sections['order'] ?? [];

        UpdateSections::forPage($page, $modules, $text, $order)
                        ->updateModules()
                        ->addTextModules()
                        ->updateTextModules()
                        ->sort();
    }

    private function saveCustomFields(Page $page, array $custom_fields)
    {
        // Keep track of any default model that will require a save on the model. This way we do it just once after
        // setting all values.
        $requires_model_save = false;

        foreach ($custom_fields as $key => $value) {
            // If custom method exists, use that to save the value, else revert to default save as column
            $methodName = 'save'. ucfirst(camel_case($key)) . 'Field';

            if (method_exists($page, $methodName)) {
                $page->$methodName($value);
            } else {
                $page->$key = $value;
                $requires_model_save = true;
            }
        }

        if ($requires_model_save) {
            $page->save();
        }
    }
}
