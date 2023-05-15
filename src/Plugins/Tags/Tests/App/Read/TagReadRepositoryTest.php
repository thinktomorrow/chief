<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Tests\App\Read;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagReadRepository;
use Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure\TaggableStub;
use Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure\TagTestHelpers;
use Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure\TestCase;

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

    public function test_it_can_get_all_tags_and_usages()
    {
        $tagModel = $this->createTagModel();
        $tagModel2 = $this->createTagModel();

        TaggableStub::migrateUp();
        $taggable = TaggableStub::create();
        $taggable2 = TaggableStub::create();

        $taggable->tags()->attach([$tagModel->id]);
        $taggable2->tags()->attach([$tagModel->id, $tagModel2->id]);

        $results = app(TagReadRepository::class)->getAll();

        $this->assertEquals(2, $results->first(fn ($tagRead) => $tagRead->getTagId() == $tagModel->id)->getUsages());
        $this->assertEquals(1, $results->first(fn ($tagRead) => $tagRead->getTagId() == $tagModel2->id)->getUsages());
    }

    public function test_it_can_get_all_tags_and_owner_references()
    {
        $tagModel = $this->createTagModel();
        $tagModel2 = $this->createTagModel();

        TaggableStub::migrateUp();
        $taggable = TaggableStub::create();
        $taggable2 = TaggableStub::create();

        $taggable->tags()->attach([$tagModel->id]);
        $taggable2->tags()->attach([$tagModel->id, $tagModel2->id]);

        $results = app(TagReadRepository::class)->getAll();

        $this->assertCount(2, $results->first(fn ($tagRead) => $tagRead->getTagId() == $tagModel->id)->getOwnerReferences());
        $this->assertCount(1, $results->first(fn ($tagRead) => $tagRead->getTagId() == $tagModel2->id)->getOwnerReferences());
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
