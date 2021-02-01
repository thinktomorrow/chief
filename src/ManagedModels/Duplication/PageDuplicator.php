<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Duplication;

class PageDuplicator implements Duplicator
{
    public function handle($sourceModel, $targetModel): void
    {
        // Dynamic attributes
        if ($sourceModel->values && $sourceModel->values instanceof DynamicAttributes) {
            $targetModel->values = $sourceModel->values;
        }

        // Translations
        foreach ($sourceModel->getTranslationsArray() as $locale => $values) {
            $targetModel->translations()->create(array_merge(['locale' => $locale], $values));
        }

        // Dynamic attributes

        // translations

        // fragments

        // assets
    }

    public function shouldApply($sourceModel, $targetModel): bool
    {
        return ($sourceModel instanceof Page);
    }

    private function shouldApplyRelations($sourceModel, $targetModel): bool
    {
        return ($sourceModel instanceof ActsAsParent && $targetModel instanceof ActsAsParent);
    }

    private function applyRelations(ActsAsParent $sourceModel, ActsAsParent $targetModel): void
    {
        foreach ($sourceModel->children() as $child) {
            $duplicatedChild = null;

            if ($child instanceof Module && $child->isPageSpecific()) {
                $duplicatedChild = $child::create([
                    'slug'           => $targetModel->title ? $targetModel->title . '-' . $child->slug : $child->slug . '-copy',
                    'owner_id'        => $targetModel->id,
                    'owner_type' => $targetModel->getMorphClass()
                ]);

                $this->moduleTemplateApplicator->handle($child, $duplicatedChild);
            } else {
                $duplicatedChild = $child;
            }

            $targetModel->adoptChild($duplicatedChild, ['sort' => $child->relation->sort]);
        }
    }
}
