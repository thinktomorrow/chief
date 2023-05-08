<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models;

use Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTable;
use Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTableFactory;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\TimeTableId;

trait HasTimeTableDefaults
{
    public function getTimeTableId(): ?TimeTableId
    {
        return $this->timetable_id ? TimeTableId::fromString($this->timetable_id) : null;
    }

    public function getTimeTable(string $locale): TimeTable
    {
        return app(TimeTableFactory::class)->create($this->getTimeTableModel(), $locale);
    }

    private function getTimeTableModel(): ?TimeTableModel
    {
        if(! $this->getTimeTableId()) {
            return null;
        }

        return app(TimeTableModel::class)->where('id', $this->getTimeTableId()->get())->first();
    }
}
