<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Repositories;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;
use Thinktomorrow\Chief\Plugins\TimeTable\Application\Read\TimeTableRead;
use Thinktomorrow\Chief\Plugins\TimeTable\Application\Read\TimeTableReadRepository;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\TimeTableModel;

class EloquentTimeTableReadRepository implements TimeTableReadRepository
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getAllTimeTablesForSelect(): array
    {
        return $this->container->get(TimeTableModel::class)::all()
            ->mapWithKeys(fn (TimeTableModel $model) => [$model->id => $model->title])
            ->all();
    }

    public function getAll(): Collection
    {
        return $this->container->get(TimeTableModel::class)::all()
            ->map(fn (TimeTableModel $model) => $this->container->get(TimeTableRead::class)::fromMappedData($model->toArray(), []));
    }
}
