<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerInterface;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagGroupRead;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagRead;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagReadRepository;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagGroupModel;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagModel;

class EloquentTagReadRepository implements TagReadRepository
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getAll(): Collection
    {
        $ownerReferences = $this->getAllOwnerReferences();

        return $this->container->get(TagModel::class)::all()
            ->map(fn (TagModel $tagModel) => $this->container->get(TagRead::class)::fromMappedData([...$tagModel->toArray(), 'owner_references' => $ownerReferences->first(fn ($ownerReferencesByTag, $tagId) => $tagId == $tagModel->id), 0]));
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

    private function getAllOwnerReferences(): Collection
    {
        return DB::table('chief_tags_pivot')
            ->get()
            ->groupBy('tag_id');
    }
}
