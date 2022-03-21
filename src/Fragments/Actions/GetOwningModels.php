<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentOwnerRepository;
use Thinktomorrow\Chief\Managers\Register\Registry;

class GetOwningModels
{
    private FragmentOwnerRepository $fragmentOwnerRepository;
    private Registry $registry;

    public function __construct(FragmentOwnerRepository $fragmentOwnerRepository, Registry $registry)
    {
        $this->fragmentOwnerRepository = $fragmentOwnerRepository;
        $this->registry = $registry;
    }

    public function get(FragmentModel $fragmentModel): array
    {
        $models = $this->fragmentOwnerRepository->getOwners($fragmentModel);

        return $models->map(function ($model) {

            $resource = $this->registry->findResourceByModel($model::class);

            return [
                'model' => $model,
                'manager' => $this->registry->findManagerByModel($model::class),
                'pageTitle' => $resource instanceof PageResource ? $resource->getPageTitle($model) : $resource->getLabel(),
            ];
        })->all();
    }
}
