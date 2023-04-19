<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Application\Read;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Plugins\TimeTable\Application\Read\TimeTableReadRepository;
use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TimeTableTestHelpers;
use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TestCase;

class TagReadRepositoryTest extends TestCase
{
    use TimeTableTestHelpers;

    public function test_it_can_get_all_tags()
    {
        $this->createDateModel();
        $this->createDateModel();

        $results = app(TimeTableReadRepository::class)->getAll();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(2, $results);
    }

    public function test_it_can_get_all_taggroups()
    {
        $this->createTimeTableModel();

        $results = app(TimeTableReadRepository::class)->getAllGroups();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(1, $results);
    }

    public function test_it_can_get_all_tags_for_select()
    {
        $this->createTimeTableModel(['id' => 666]);
        $modelFirst = $this->createDateModel(['taggroup_id' => 666]);
        $modelSecond = $this->createDateModel();

        $results = app(TimeTableReadRepository::class)->getAllForSelect();

        $this->assertIsArray($results);
        $this->assertCount(2, $results);
        $this->assertEquals([
            $modelFirst->id => $modelFirst->label,
            $modelSecond->id => $modelSecond->label,
        ], $results);
    }

    public function test_it_can_order_tags()
    {
    }
}
