<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure;

use Illuminate\Testing\TestResponse;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\DateModel;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\TimeTableModel;

trait TimeTableTestHelpers
{
    protected function createDateModel(array $values = []): DateModel
    {
        return DateModel::create(array_merge([
            'color' => '#333333',
            'taggroup_id' => '666',
            'label' => 'in review',
        ], $values));
    }

    protected function performDateStore(array $values = []): TestResponse
    {
        return $this->asAdmin()->post(route('chief.timetable_dates.store'), array_merge([
            'label' => 'reviewing',
            'color' => '#333333',
            'taggroup_id' => '1',
        ], $values));
    }

    protected function performDateUpdate($tagId, array $values = []): TestResponse
    {
        return $this->asAdmin()->put(route('chief.timetable_dates.update', $tagId), array_merge([
            'label' => 'reviewed',
            'color' => '#666666',
            'taggroup_id' => '2',
        ], $values));
    }

    protected function performDateDelete($tagId): TestResponse
    {
        return $this->asAdmin()->delete(route('chief.timetable_dates.delete', $tagId));
    }

    protected function createTimeTableModel(array $values = []): TimeTableModel
    {
        return TimeTableModel::create(array_merge([
            'label' => 'Openingsuren Herenthout',
        ], $values));
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
            'days' => [
                [
                    'day' => '1',
                    'hours' => [
                        [
                            'from' => '9:00',
                            'until' => '12:00',
                        ],
                        [
                            'from' => '13:00',
                            'until' => '16:30',
                        ],
                    ],
                    'content' => ['nl' => 'open voor zaken', 'en' => 'open for business'],
                ],
                [
                    'day' => '2',
                    'hours' => [
                        [
                            'from' => '10:00',
                            'until' => '12:00',
                        ],
                        [
                            'from' => '14:00',
                            'until' => '16:30',
                        ],
                    ],
                    'content' => [],
                ],
            ],
        ], $values));
    }

    protected function performTimeTableDelete($tagId): TestResponse
    {
        return $this->asAdmin()->delete(route('chief.timetables.delete', $tagId));
    }
}
