<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\HasAsset;
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
        $copiedModel = $model->replicate(['id', 'created_at', 'updated_at']);

        $this->resetAstrotomicTranslations($copiedModel);

        if ($model->$titleKey) {
            $this->copyTitle($model, $titleKey, $copiedModel);
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

    /**
     * @param Model $copiedModel
     * @return void
     */
    private function resetAstrotomicTranslations(Model $copiedModel): void
    {
        if (!$copiedModel->relationLoaded('translations')) {
            return;
        }

        $transModels = new Collection();

        foreach ($copiedModel->getRelation('translations') as $translation) {
            $transModels->push($translation->replicate(['id', 'owner_id', 'created_at', 'updated_at']));
        }

        $copiedModel->unsetRelation('translations');
        $copiedModel->setRelation('translations', $transModels);
    }

    /**
     * @param Model $model
     * @param string $titleKey
     * @param Model $copiedModel
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     * @throws \Thinktomorrow\Chief\Managers\Exceptions\MissingResourceRegistration
     */
    private function copyTitle(Model $model, string $titleKey, Model $copiedModel): void
    {
        // Default when title is no dynamic field
        if (!public_method_exists($model, 'dynamic') || !$model->isDynamic($titleKey)) {
            $copiedModel->$titleKey = $model->$titleKey;
            return;
        }

        // Dynamic field - Is title field localized or not?
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
}
