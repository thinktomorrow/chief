<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Old\PageBuilder;

use Thinktomorrow\Chief\Legacy\Pages\Page;
use Thinktomorrow\Chief\Sets\SetReference;
use Thinktomorrow\Chief\Sets\StoredSetReference;
use Thinktomorrow\Chief\Modules\Presets\TextModule;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Astrotomic\Translatable\Contracts\Translatable;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\PagebuilderField;
use Thinktomorrow\Chief\Modules\Presets\PagetitleModule;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReferencePresenter;

final class PageBuilder
{
    public function save(ActsAsParent $model, array $input)
    {
        $sections = data_get($input, 'sections', []);

        $modules = $sections['modules'] ?? [];
        $text = $sections['text'] ?? [];
        $sets = $sections['pagesets'] ?? [];
        $order = $sections['order'] ?? [];

        UpdateSections::forModel($model, $modules, $text, $sets, $order)
            ->updateModules()
            ->updateSets()
            ->addTextModules()
            ->updateTextModules()
            ->sort();
    }

    public function field(ActsAsParent $model): PagebuilderField
    {
        $availableChildren = AvailableChildren::forParent($model);

        // Only include modules of this model or shared ones.
        $modules = $availableChildren->onlyModules()->reject(function ($module) use ($model) {
            return $module->owner_id != null && $module->owner_id != $model->id;
        });

        $available_modules = ModelReferencePresenter::toGroupedSelectValues($modules)->toArray();
        $available_pages = ModelReferencePresenter::toGroupedSelectValues($availableChildren->onlyPages())->toArray();
        $available_sets = ModelReferencePresenter::toGroupedSelectValues($availableChildren->onlySets())->toArray();

        // Current sections
        $sections = $model->children()->map(function ($section, $index) use($model) {
//            if ($section instanceof TranslatableContract) {
//
//            }

            $section = $this->injectTranslations($section);

            return [
                // Module reference is by id.
                'id'      => $section->modelReference()->get(),

                // Key is a separate value to assign each individual module.
                // This is separate from id to avoid vue key binding conflicts.
                'key'     => $section->modelReference()->get(),
                'type'    => $this->guessPagebuilderSectionType($section),
                'slug'    => $section->slug,
                'editUrl' => $this->findEditUrl($section, $model),
                'sort'    => $index,
                'trans'   => $section->trans ?? [],
            ];
        })->toArray();

        return PagebuilderField::make('sections')
            ->locales()
            ->sections($sections)
            ->availablePages($available_pages)
            ->availableModules($available_modules)
            ->availableSets($available_sets)
            ->tag('pagebuilder');
    }

    // Make all translations available for our pagebuilder. The pagebuilder expects a trans array per locale.
    private function injectTranslations($model)
    {
        $trans = [];

        // Has dynamic attributes
        if(public_method_exists($model, 'dynamic')) {
            foreach (config('chief.locales') as $locale) {
                $trans[$locale] = [];

                foreach($model->rawDynamicValues() as $key => $values) {
                    if(is_array($values)) {
                        foreach($values as $dynamicLocale => $dynamicValue) {
                            if($dynamicLocale === $locale) {
                                $trans[$locale][$key] = $dynamicValue;
                            }
                        }
                    }
                }
            }
        }

        // Astrotomic translatable
        if($model instanceof Translatable) {
            foreach (config('chief.locales') as $locale) {
                if(!isset($trans[$locale])) $trans[$locale] = [];

                $trans[$locale] = array_merge($trans[$locale], $model->getTranslation($locale)->toArray());
            }
        }

        $model->trans = $trans;

        return $model;
    }

    /**
     * @param $model
     * @return string|null
     */
    private function findEditUrl($model, $parent): ?string
    {
        if (!$model instanceof ManagedModel) {
            return null;
        }

        return app(Registry::class)->manager($model::managedModelKey())
                                            ->route('edit', $model, $parent->getMorphClass(), $parent->id);
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
