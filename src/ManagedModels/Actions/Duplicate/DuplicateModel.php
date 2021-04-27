<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Illuminate\Database\Eloquent\Model;

class DuplicateModel
{
    public function handle(Model $model): Model
    {
        // Otherwise do a full copy of the fragment instead
        $copiedModel = $model->replicate();
        $copiedModel->id = null;
        $copiedModel->save();

        return $copiedModel;

        // TODO: Assets
//        foreach ($model->assets() as $asset) {
//            $copiedFragment->assetRelation()->attach($asset, ['type' => $asset->pivot->type, 'locale' => $asset->pivot->locale, 'order' => $asset->pivot->order]);
//        }
    }
}
