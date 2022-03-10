<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Forms\Fields;

class DuplicateModel
{
    public function handle(Model $model): Model
    {
        $copiedModel = $model->replicate();
        $copiedModel->id = null;

        if ($model->title && public_method_exists($model, 'dynamic') && $model->isDynamic('title')) {

            // Is title field localized or not?
            $isTitleLocalized = ($field = Fields::make($model->fields())->find('title')) ? $field->hasLocales() : false;

            if ($isTitleLocalized) {
                $locales = config('chief.locales', []);
                $defaultLocale = reset($locales);
                $copiedModel->setDynamic('title', '[Copy] ' . $model->dynamic('title', $defaultLocale, $model->dynamic('title')), $defaultLocale);
            } else {
                $copiedModel->setDynamic('title', '[Copy] ' . $model->title);
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
