<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Templates;

use Thinktomorrow\Chief\DynamicAttributes\DynamicAttributes;
use Thinktomorrow\Chief\Modules\Module;
use Webmozart\Assert\Assert;

class ModuleTemplateApplicator implements TemplateApplicator
{
    public function __construct()
    {
    }

    public function handle($sourceModel, $targetModel): void
    {
        Assert::isInstanceOf($sourceModel, Module::class);
        Assert::isInstanceOf($targetModel, Module::class);

        // Dynamic attributes
        if ($sourceModel->values && $sourceModel->values instanceof DynamicAttributes) {
            $targetModel->values = $sourceModel->values;
        }

        // Translations
        foreach ($sourceModel->getTranslationsArray() as $locale => $values) {
            $targetModel->translations()->create(array_merge(['locale' => $locale], $values));
        }

        // Fragments
        if (method_exists($sourceModel, 'fragments') && method_exists($targetModel, 'fragments')) {
            foreach ($sourceModel->fragments()->get() as $fragmentModel) {
                $targetModel->fragments()->create([
                    'key'    => $fragmentModel->key,
                    'values' => $fragmentModel->values,
                ]);
            }
        }

        $targetModel->save();

        // Assets
        foreach ($sourceModel->assets() as $asset) {
            $targetModel->assetRelation()->attach($asset, ['type' => $asset->pivot->type, 'locale' => $asset->pivot->locale, 'order' => $asset->pivot->order]);
        }
    }

    public function shouldApply($sourceModel, $targetModel): bool
    {
        return ($sourceModel instanceof Module && $targetModel instanceof Module);
    }
}
