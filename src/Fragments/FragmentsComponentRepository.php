<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;

class FragmentsComponentRepository
{
    private FragmentRepository $fragmentRepository;

    private FragmentsOwner $owner;

    /** @var null|Collection */
    private $fragments;
    /**
     * @var Registry
     */
    private Registry $registry;

    public function __construct(FragmentRepository $fragmentRepository, Registry $registry, FragmentsOwner $owner)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->registry = $registry;
        $this->owner = $owner;
    }

    public function getFragments(): Collection
    {
        return $this->fragments()->map(function (Fragmentable $model) {
            return [
                'model'   => $model,
                'manager' => app(Registry::class)->manager($model::managedModelKey()),
            ];
        });
    }

    public function getManager(): Manager
    {
        return $this->registry->manager($this->owner::managedModelKey());
    }

    public function getAllowedFragments(): array
    {
        return array_map(function ($fragmentableClass) {
            $modelClass = $this->registry->modelClass($fragmentableClass::managedModelKey());

            return [
                'manager' => $this->registry->manager($fragmentableClass::managedModelKey()),
                'model'   => new $modelClass(),
            ];
        }, $this->owner->allowedFragments());
    }

    public function getSharedFragments(): array
    {
        $fragmentModelIds = $this->fragments()->map(fn($fragment) => $fragment->fragmentModel())->pluck('id')->toArray();

        return $this->fragmentRepository->shared()->reject(function ($fragmentable) use ($fragmentModelIds) {
            return in_array($fragmentable->fragmentModel()->id, $fragmentModelIds);
        })->map(function ($fragmentable) {
            return [
                'manager' => $this->registry->manager($fragmentable::managedModelKey()),
                'model'   => $fragmentable,
            ];
        })->all();
    }

    private function fragments(): Collection
    {
        if ($this->fragments) {
            return $this->fragments;
        }

        return $this->fragments = $this->fragmentRepository->getByOwner($this->owner->ownerModel());
    }
}
