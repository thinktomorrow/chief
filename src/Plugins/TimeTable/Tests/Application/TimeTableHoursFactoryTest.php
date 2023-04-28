<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Application;

use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TestCase;
use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TimeTableTestHelpers;

class TimeTableHoursFactoryTest extends TestCase
{
    use TimeTableTestHelpers;

    public function test_it_can_create_mapping()
    {
        $model = $this->createTimeTableModel();
        $this->createDateModel();
    }

}
