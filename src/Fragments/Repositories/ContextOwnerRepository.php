<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Repositories;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class ContextOwnerRepository
{
    private ContextRepository $contextRepository;

    public function __construct(ContextRepository $contextRepository)
    {
        $this->contextRepository = $contextRepository;
    }

    public function getOwnersByFragment(string $fragmentId): Collection
    {
        $models = $this->contextRepository->getByFragment($fragmentId);

        return $models
            ->map(fn ($model) => $this->ownerFactory($model->owner_type, $model->owner_id))
            ->unique()
            ->map(function ($model) {
                return ($model instanceof FragmentModel) ? $this->fragmentFactory($model) : $model;
            });
    }

    /**
     * This retrieves the root owners (resources) and not
     * any fragment owners in case of nested fragments
     */
    public function getRootOwnersByFragment(FragmentModel $fragmentModel): Collection
    {
        $models = ContextModel::owning($fragmentModel)
            ->map(fn ($model) => $this->ownerFactory($model->owner_type, $model->owner_id));

        $result = collect();

        foreach($models as $model) {
            if($model instanceof FragmentModel) {
                $result = $result->merge($this->getRootOwnersByFragment($model));
            } else {
                $result->push($model);
            }
        }

        return $result;
    }

    public function findOwner(string $contextId): ContextOwner
    {
        $context = ContextModel::findOrFail($contextId);

        return $this->ownerFactory($context->owner_type, $context->owner_id);
    }

    private function ownerFactory(string $key, $id): ContextOwner
    {
        $key = Relation::getMorphedModel($key) ?? $key;

        return ModelReference::make($key, $id)->instance();
    }

    private function fragmentFactory(FragmentModel $fragmentModel): Fragment
    {
        return app(FragmentFactory::class)->create($fragmentModel);
    }
}
