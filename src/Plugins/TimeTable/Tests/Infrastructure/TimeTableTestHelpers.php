<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure;

use Illuminate\Testing\TestResponse;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\DateModel;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\DayModel;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\TimeTableModel;

trait TimeTableTestHelpers
{
    protected function createDateModel(array $values = [], array $timeTableIds = []): DateModel
    {
        $model = DateModel::create(array_merge([
            'date' => now()->addWeek(),
            'slots' => [
                ['from' => '08:30', 'until' => '12:00'],
                ['from' => '13:00', 'until' => '17:00'],
            ],
            'content' => ['nl' => 'speciale dag', 'en' => 'special day'],
        ], $values));

        $model->timetables()->sync($timeTableIds);

        return $model;
    }

    protected function performDateStore(array $values = []): TestResponse
    {
        return $this->asAdmin()->post(route('chief.timetable_dates.store'), array_merge([
            'date' => '2022-03-05',
            'slots' => [
                ['from' => '08:30', 'until' => '12:00'],
                ['from' => '13:00', 'until' => '17:00'],
            ],
            'content' => ['nl' => 'speciale dag', 'en' => 'special day'],
        ], $values));
    }

    protected function performDateUpdate($tagId, array $values = []): TestResponse
    {
        return $this->asAdmin()->put(route('chief.timetable_dates.update', $tagId), array_merge([
            'date' => '2022-03-06',
            'slots' => [
                ['from' => '08:45', 'until' => '12:00'],
                ['from' => '14:00', 'until' => '17:00'],
            ],
            'content' => ['nl' => 'halve speciale dag', 'en' => 'half special day'],
        ], $values));
    }


    protected function performDateDelete($tagId): TestResponse
    {
        return $this->asAdmin()->delete(route('chief.timetable_dates.delete', $tagId));
    }

    protected function createDayModel($timetable_id, array $values = []): DayModel
    {
        return DayModel::create(array_merge([
            'timetable_id' => $timetable_id,
            'weekday' => 1,
            'slots' => [
                ['from' => '08:30', 'until' => '12:00'],
                ['from' => '13:00', 'until' => '17:00'],
            ],
            'content' => ['nl' => 'speciale dag', 'en' => 'special day'],
        ], $values));
    }

    protected function performDayUpdate($dayId, array $values = []): TestResponse
    {
        return $this->asAdmin()->put(route('chief.timetable_days.update', $dayId), array_merge([
            'slots' => [
                ['from' => '08:45', 'until' => '12:00'],
                ['from' => '14:00', 'until' => '17:00'],
            ],
            'content' => ['nl' => 'halve speciale dag', 'en' => 'half special day'],
        ], $values));
    }

    protected function createTimeTableModel(array $values = []): TimeTableModel
    {
        return TimeTableModel::create(array_merge([
            'label' => 'Openingsuren Herenthout',
        ], $values));
    }

    public function createDays(TimeTableModel $model)
    {
        app(DayModel::class)::createWeekWithDefaults($model);
    }

    protected function performTimeTableStore(array $values = []): TestResponse
    {
        return $this->asAdmin()->post(route('chief.timetables.store'), array_merge([
            'label' => 'Openingsuren Herenthout',
        ], $values));
    }

    protected function performTimeTableUpdate($tagId, array $values = []): TestResponse
    {
        return $this->asAdmin()->put(route('chief.timetables.update', $tagId), array_merge([
            'label' => 'Openingsuren Herentals',
        ], $values));
    }

    protected function performTimeTableDelete($tagId): TestResponse
    {
        return $this->asAdmin()->delete(route('chief.timetables.delete', $tagId));
    }
}
