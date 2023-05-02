<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Repositories;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;
use Thinktomorrow\Chief\Plugins\TimeTable\App\Read\TimeTableRead;
use Thinktomorrow\Chief\Plugins\TimeTable\App\Read\TimeTableReadRepository;
use Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTable;
use Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTableFactory;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\TimeTableModel;

class EloquentTimeTableReadRepository implements TimeTableReadRepository
{
    private ContainerInterface $container;
    private TimeTableFactory $timeTableFactory;

    public function __construct(ContainerInterface $container, TimeTableFactory $timeTableFactory)
    {
        $this->container = $container;
        $this->timeTableFactory = $timeTableFactory;
    }

    public function getAllTimeTablesForSelect(): array
    {
        return $this->container->get(TimeTableModel::class)::all()
            ->mapWithKeys(fn (TimeTableModel $model) => [$model->id => $model->label])
            ->all();
    }
}
