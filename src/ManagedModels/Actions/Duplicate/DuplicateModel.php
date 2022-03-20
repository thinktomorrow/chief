<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Managers\Register\Registry;

class DuplicateModel
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function handle(Model $model, string $titleKey = 'title'): Model
    {
        $copiedModel = $model->replicate();
        $copiedModel->id = null;

        if ($model->$titleKey && public_method_exists($model, 'dynamic') && $model->isDynamic($titleKey)) {

            // Is title field localized or not?
            $field = $this->registry->findResourceByModel($model::class)->field($model, $titleKey);
            $isTitleLocalized = $field ? $field->hasLocales() : false;

            if ($isTitleLocalized) {
                $locales = config('chief.locales', []);
                $defaultLocale = reset($locales);
                $copiedModel->setDynamic($titleKey, '[Copy] ' . $model->dynamic($titleKey, $defaultLocale, $model->dynamic($titleKey)), $defaultLocale);
            } else {
                $copiedModel->setDynamic($titleKey, '[Copy] ' . $model->$titleKey);
            }
        }

        $copiedModel->created_at = now();
        $copiedModel->updated_at = now();
        $copiedModel->save();

        if ($model instanceof HasAsset) {
            foreach ($model->assetRelation()->get() as $asset) {
                $copiedModel->assetRelation()->attach($asset, [
                    'type' => $asset->pivot->type,
                    'locale' => $asset->pivot->locale,
                    'order' => $asset->pivot->order,
                ]);
            }
        }

        return $copiedModel;
    }
}
