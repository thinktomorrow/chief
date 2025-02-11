<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\App;

use Spatie\OpeningHours\Exceptions\OverlappingTimeRanges;
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
            ...($content = $model->exceptions->first(fn ($exception) => $exception->date->format('Y-m-d') == $dateModel->date->format('Y-m-d'))?->getContent($locale)) ? ['data' => $content] : [],
        ]]);

        return $this->createTimeTable($items);

    }

    public function createWithoutExceptions(TimeTableModel $model, string $locale): TimeTable
    {
        $items = $model->days->mapWithKeys(fn (DayModel $dayModel) => [$dayModel->getDayInEnglish() => [
            ...$dayModel->getSlots()->getSlotsAsString(),
            ...($content = $model->days->first(fn ($day) => $day->weekday == $dayModel->weekday)?->getContent($locale)) ? ['data' => $content] : [],
        ]]);

        return $this->createTimeTable($items);
    }

    public function createTimeTable($items): \Spatie\OpeningHours\OpeningHours|TimeTable
    {
        try {
            return TimeTable::create($items->all());
        } catch (OverlappingTimeRanges $e) {
            report($e);

            return TimeTable::createAndMergeOverlappingRanges($items->all());
        }
    }
}
