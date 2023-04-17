<?php

namespace Thinktomorrow\Chief\Admin\Tags\Read;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;
use Thinktomorrow\Chief\Admin\Tags\TagGroupModel;
use Thinktomorrow\Chief\Admin\Tags\TagModel;

class DefaultTagReadRepository implements TagReadRepository
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getAll(): Collection
    {
        return $this->container->get(TagModel::class)::all()
            ->map(fn (TagModel $tagModel) => $this->container->get(TagRead::class)::fromMappedData($tagModel->toArray()));
    }

    public function getAllGroups(): Collection
    {
        return $this->container->get(TagGroupModel::class)::all()
            ->map(fn (TagGroupModel $tagGroupModel) => $this->container->get(TagGroupRead::class)::fromMappedData($tagGroupModel->toArray()));
    }

    public function getAllForSelect(): array
    {
        return $this->container->get(TagModel::class)::with('taggroups')
            ->get()
            ->mapWithKeys(fn (TagModel $model) => [$model->id => $model->label])
            ->all();
    }

    public function getAllGroupsForSelect(): array
    {
        return $this->container->get(TagGroupModel::class)::all()
            ->mapWithKeys(fn (TagGroupModel $model) => [$model->id => $model->label])
            ->all();
    }
}
