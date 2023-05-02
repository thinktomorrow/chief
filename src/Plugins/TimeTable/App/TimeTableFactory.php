<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\App;

use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\DateModel;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\DayModel;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\TimeTableModel;

class TimeTableFactory
{
    public function create(TimeTableModel $model, string $locale): TimeTable
    {
        $items = $model->days->mapWithKeys(fn (DayModel $dayModel) => [$dayModel->getDayInEnglish() => [
            ...$dayModel->getSlots()->getSlotsAsString(),
            ...($content = $model->days->first(fn ($day) => $day->weekday == $dayModel->weekday)?->getContent($locale)) ? ['data' => $content] : [],
        ]]);

        $items['exceptions'] = $model->exceptions->mapWithKeys(fn (DateModel $dateModel) => [$dateModel->date->format('Y-m-d') => [
            ...$dateModel->getSlots()->getSlotsAsString(),
            ...($content = $model->exceptions->first(fn ($day) => $day->weekday == $dateModel->weekday)?->getContent($locale)) ? ['data' => $content] : [],
        ]]);

        return TimeTable::createAndMergeOverlappingRanges($items->all());

//        return TimeTable::create([
//            ...$items->all(),
//            'overflow' => true
//        ]);
    }
}
