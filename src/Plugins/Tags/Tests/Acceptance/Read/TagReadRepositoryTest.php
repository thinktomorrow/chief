<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Tests\Application\Read;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagReadRepository;
use Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure\TestCase;
use Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure\TagTestHelpers;

class TagReadRepositoryTest extends TestCase
{
    use TagTestHelpers;

    public function test_it_can_get_all_tags()
    {
        $this->createTagModel();
        $this->createTagModel();

        $results = app(TagReadRepository::class)->getAll();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(2, $results);
    }

    public function test_it_can_get_all_taggroups()
    {
        $this->createTaggroupModel();

        $results = app(TagReadRepository::class)->getAllGroups();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(1, $results);
    }

    public function test_it_can_get_all_tags_for_select()
    {
        $this->createTaggroupModel(['id' => 666]);
        $modelFirst = $this->createTagModel(['taggroup_id' => 666]);
        $modelSecond = $this->createTagModel();

        $results = app(TagReadRepository::class)->getAllForSelect();

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
