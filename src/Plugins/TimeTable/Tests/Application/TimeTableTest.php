<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Application;

use Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTableFactory;
use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TestCase;
use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TimeTableTestHelpers;

class TimeTableTest extends TestCase
{
    use TimeTableTestHelpers;

    public function test_it_can_create_timetable_for_week()
    {
        $model = $this->createTimeTableModel();
        $this->createDays($model);

        $timetable = app(TimeTableFactory::class)->create($model, 'nl');

        $this->assertCount(7, $timetable->forCurrentWeek());
        $this->assertCount(14, $timetable->forWeeks(2));
    }

    public function test_it_can_create_timetable_for_period()
    {
        $model = $this->createTimeTableModel();
        $this->createDays($model);

        $timetable = app(TimeTableFactory::class)->create($model, 'nl');

        $this->assertCount(9, $timetable->forDays(now(), now()->addDays(8)));
    }
}
