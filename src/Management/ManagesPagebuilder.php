<?php


namespace Thinktomorrow\Chief\Management;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Sets\SetReference;
use Thinktomorrow\Chief\Modules\TextModule;
use Thinktomorrow\Chief\Modules\PagetitleModule;
use Thinktomorrow\Chief\Sets\StoredSetReference;
use Thinktomorrow\Chief\PageBuilder\UpdateSections;
use Thinktomorrow\Chief\Relations\AvailableChildren;
use Thinktomorrow\Chief\Fields\Types\PagebuilderField;
use Thinktomorrow\Chief\FlatReferences\FlatReferencePresenter;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableContract;

trait ManagesPagebuilder
{
    /**
     * The naming convention is important here because to hook into the saving
     * flow it needs to have the save<key>Field method naming.
     *
     * @param PagebuilderField $field
     * @param Request $request
     */
    public function saveSectionsField(PagebuilderField $field, Request $request)
    {
        $sections = $request->get('sections', []);

        $modules = $sections['modules'] ?? [];
        $text    = $sections['text'] ?? [];
        $sets    = $sections['pagesets'] ?? [];
        $order   = $sections['order'] ?? [];

        UpdateSections::forModel($this->model, $modules, $text, $sets, $order)
            ->updateModules()
            ->updateSets()
            ->addTextModules()
            ->updateTextModules()
            ->sort();
    }

    protected function createPagebuilderField(): PagebuilderField
    {
        $model = $this->model;

        $availableChildren = AvailableChildren::forParent($model);

        $modules = $availableChildren->onlyModules()->reject(function ($module) use ($model) {
            return $module->page_id != null && $module->page_id != $model->id;
        });

        $available_modules = FlatReferencePresenter::toGroupedSelectValues($modules)->toArray();
        $available_pages = FlatReferencePresenter::toGroupedSelectValues($availableChildren->onlyPages())->toArray();
        $available_sets = FlatReferencePresenter::toGroupedSelectValues($availableChildren->onlySets())->toArray();

        // Current sections
        $sections = $model->children()->map(function ($section, $index) {
            if ($section instanceof TranslatableContract) {
                $section->injectTranslationForForm();
            }

            return [
                // Module reference is by id.
                'id'         => $section->flatReference()->get(),

                // Key is a separate value to assign each individual module.
                // This is separate from id to avoid vue key binding conflicts.
                'key'        => $section->flatReference()->get(),
                'type'       => $this->guessPagebuilderSectionType($section),
                'slug'       => $section->slug,
                'editUrl'    => $this->findEditUrl($section),
                'sort'       => $index,
                'trans'      => $section->trans ?? [],
            ];
        })->toArray();

        return PagebuilderField::make('sections')
                                ->translatable($this->model->availableLocales())
                                ->sections($sections)
                                ->availablePages($available_pages)
                                ->availableModules($available_modules)
                                ->availableSets($available_sets);
    }

    /**
     * @param $model
     * @return string|null
     */
    private function findEditUrl($model): ?string
    {
        if (! $model instanceof ManagedModel) {
            return null;
        }

        try {
            return app(Managers::class)->findByModel($model)->route('edit');
        } catch (NonRegisteredManager $e) {
            return null;
        }
    }

    /**
     * Section type is the grouping inside the pagebuilder (specifically the menu)
     *
     * @param $section
     * @return string
     */
    private function guessPagebuilderSectionType($section)
    {
        if ($section instanceof TextModule) {
            return 'text';
        }

        if ($section instanceof PagetitleModule) {
            return 'pagetitle';
        }

        if ($section instanceof StoredSetReference || $section instanceof SetReference) {
            // TODO: clean this up and replace 'pageset' with 'set';
            return 'pageset';
        }

        if ($section instanceof Page) {
            return 'page';
        }

        // We want all other types to be registered as modules
        return 'module';
    }
}
