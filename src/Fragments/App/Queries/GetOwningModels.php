<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Queries;

use Thinktomorrow\Chief\Fragments\Domain\Models\ContextOwnerRepository;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\PageResource;

class GetOwningModels
{
    private ContextOwnerRepository $fragmentOwnerRepository;
    private ContextRepository $contextRepository;
    private Registry $registry;

    public function __construct(ContextRepository $contextRepository, ContextOwnerRepository $fragmentOwnerRepository, Registry $registry)
    {
        $this->fragmentOwnerRepository = $fragmentOwnerRepository;
        $this->contextRepository = $contextRepository;
        $this->registry = $registry;
    }

    public function get(string $fragmentId): array
    {
        $models = $this->fragmentOwnerRepository->getOwnersByFragment($fragmentId);

        return $models->map(function ($model) {
            $resource = $model instanceof Fragment
                ? $model
                : $this->registry->findResourceByModel($model::class);

            return [
                'model' => $model,
                'manager' => $model instanceof Fragment ? null : $this->registry->findManagerByModel($model::class),
                'pageTitle' => $resource instanceof PageResource ? $resource->getPageTitle($model) : $resource->getLabel(),
            ];
        })->all();
    }

    /**
     * Get the count of the different owners. This count does not reflect
     * the amount of contexts since each owner can own multiple contexts.
     */
    public function getCount(string $fragmentId): int
    {
        return $this->contextRepository->getByFragment($fragmentId)
            ->groupBy(fn ($row) => $row->owner_type.'_'.$row->owner_id)
            ->count();
    }
}
