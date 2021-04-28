<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Database;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class FragmentOwnerRepository
{
    public function getOwners(FragmentModel $fragmentModel): Collection
    {
        $models = ContextModel::owning($fragmentModel);

        return $models->map(fn ($model) => $this->ownerFactory($model->owner_type, $model->owner_id));
    }

    private function ownerFactory(string $model_reference, $id)
    {
        $model_reference = Relation::getMorphedModel($model_reference) ?? $model_reference;

        return (new ModelReference($model_reference, $id))->instance();
    }
}