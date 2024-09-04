<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models;

use Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTable;
use Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTableFactory;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\TimeTableId;

trait HasTimeTableDefaults
{
    public function getTimeTableId(): ?TimeTableId
    {
        if (! $this->timetable_id) {
            return null;
        }

        return TimeTableId::fromString($this->timetable_id);
    }

    public function getTimeTable(string $locale): ?TimeTable
    {
        $timeTableModel = $this->getTimeTableModel();

        if (! $timeTableModel) {
            return null;
        }

        return app(TimeTableFactory::class)->create($timeTableModel, $locale);
    }

    private function getTimeTableModel(): ?TimeTableModel
    {
        $timeTableId = $this->getTimeTableId();

        if (! $timeTableId) {
            return null;
        }

        return app(TimeTableModel::class)->where('id', $timeTableId->get())->first();
    }
}
