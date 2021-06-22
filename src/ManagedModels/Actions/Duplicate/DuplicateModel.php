<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Illuminate\Database\Eloquent\Model;

class DuplicateModel
{
    public function handle(Model $model): Model
    {
        $copiedModel = $model->replicate();
        $copiedModel->id = null;

        if ($model->title && public_method_exists($model, 'dynamic')) {
            $locales = config('chief.locales', []);
            $defaultLocale = reset($locales);
            $copiedModel->setDynamic('title', '[Copy] ' . $model->dynamic('title', $defaultLocale), $defaultLocale);
        }

        $copiedModel->created_at = now();
        $copiedModel->updated_at = now();
        $copiedModel->save();

        foreach ($model->assetRelation()->get() as $asset) {
            $copiedModel->assetRelation()->attach($asset, [
                'type' => $asset->pivot->type,
                'locale' => $asset->pivot->locale,
                'order' => $asset->pivot->order,
            ]);
        }

        return $copiedModel;
    }
}
