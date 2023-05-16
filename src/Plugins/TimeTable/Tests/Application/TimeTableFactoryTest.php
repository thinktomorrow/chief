<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Application;

use Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTable;
use Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTableFactory;
use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TestCase;
use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TimeTableTestHelpers;

class TimeTableFactoryTest extends TestCase
{
    use TimeTableTestHelpers;

    public function test_it_can_create_mapping()
    {
        $model = $this->createTimeTableModel();
        $this->createDays($model);

        $result = app(TimeTableFactory::class)->create($model, 'nl');

        $this->assertInstanceOf(TimeTable::class, $result);
    }

    public function test_it_can_create_mapping_with_content()
    {
        $model = $this->createTimeTableModel();

        $this->createDayModel($model->id, [
            'weekday' => 1,
            'content' => ['nl' => 'dit is een speciale dag', 'en' => 'this is a special day'],
        ]);

        $this->createDayModel($model->id, [
            'weekday' => 2,
            'content' => [],
        ]);

        /** @var TimeTable $result */
        $result = app(TimeTableFactory::class)->create($model, 'nl');
        $this->assertEquals('dit is een speciale dag', $result->forDay('monday')->getData());
        $this->assertNull($result->forDay('tuesday')->getData());

        $resultEn = app(TimeTableFactory::class)->create($model, 'en');
        $this->assertEquals('this is a special day', $resultEn->forDay('monday')->getData());
        $this->assertNull($resultEn->forDay('tuesday')->getData());

        $this->assertInstanceOf(TimeTable::class, $result);
    }

    public function test_it_can_create_mapping_with_exceptions()
    {
        $model = $this->createTimeTableModel();
        $this->createDays($model);

        $this->createDateModel([
            'date' => '2022-05-09',
        ], [$model->id]);

        $result = app(TimeTableFactory::class)->create($model, 'nl');

        $this->assertCount(1, $result->exceptions());
    }

}
