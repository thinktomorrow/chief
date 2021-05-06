<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Database;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class FragmentOwnerRepository
{
    public function getOwners(FragmentModel $fragmentModel): Collection
    {
        $models = ContextModel::owning($fragmentModel);

        return $models
            ->map(fn ($model) => $this->ownerFactory($model->owner_type, $model->owner_id))
            ->map(function ($model) {
                return ($model instanceof FragmentModel) ? $this->fragmentFactory($model) : $model;
            });
    }

    private function ownerFactory(string $model_reference, $id)
    {
        $model_reference = Relation::getMorphedModel($model_reference) ?? $model_reference;

        return ModelReference::make($model_reference, $id)->instance();
    }

    private function fragmentFactory(FragmentModel $fragmentModel): Fragmentable
    {
        return ModelReference::fromString($fragmentModel->model_reference)
            ->instance()
            ->setFragmentModel($fragmentModel);
    }
}
