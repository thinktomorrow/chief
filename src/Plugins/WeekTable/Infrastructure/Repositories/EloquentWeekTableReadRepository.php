<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Repositories;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;
use Thinktomorrow\Chief\Plugins\WeekTable\Application\Read\WeekTableRead;
use Thinktomorrow\Chief\Plugins\WeekTable\Application\Read\WeekTableReadRepository;
use Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Models\WeekTableModel;

class EloquentWeekTableReadRepository implements WeekTableReadRepository
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getAllWeekTablesForSelect(): array
    {
        return $this->container->get(WeekTableModel::class)::all()
            ->mapWithKeys(fn (WeekTableModel $model) => [$model->id => $model->title])
            ->all();
    }

    public function getAll(): Collection
    {
        return $this->container->get(WeekTableModel::class)::all()
            ->map(fn (WeekTableModel $model) => $this->container->get(WeekTableRead::class)::fromMappedData($model->toArray()));
    }
}
