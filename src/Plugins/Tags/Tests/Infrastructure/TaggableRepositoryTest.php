<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure;

use Generator;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagRead;
use Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Repositories\EloquentTaggableRepository;

class TaggableRepositoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        TaggableStub::migrateUp();
    }

    public function test_it_can_sync_tags()
    {
        $taggable = TaggableStub::create();
        $tagModel = $this->createTagModel();
        $tagModel2 = $this->createTagModel();

        foreach ($this->repositories() as $repository) {
            $repository->syncTags($taggable->getMorphClass(), [$taggable->id], [$tagModel->id]);

            $this->assertCount(1, $taggable->fresh()->tags);
            $this->assertInstanceOf(TagRead::class, $taggable->fresh()->getTags()->first());

            $repository->syncTags($taggable->getMorphClass(), [$taggable->id], [$tagModel->id, $tagModel2->id]);

            $this->assertCount(2, $taggable->fresh()->tags);
            $this->assertEquals($tagModel->id, $taggable->fresh()->tags->first()->id);
            $this->assertEquals($tagModel2->id, $taggable->fresh()->tags[1]->id);

            $repository->syncTags($taggable->getMorphClass(), [$taggable->id], [$tagModel2->id]);

            $this->assertCount(1, $taggable->fresh()->tags);
            $this->assertEquals($tagModel2->id, $taggable->fresh()->tags->first()->id);
        }
    }

    public function test_it_can_attach_tags()
    {
        $taggable = TaggableStub::create();
        $taggable2 = TaggableStub::create();
        $tagModel = $this->createTagModel();

        foreach ($this->repositories() as $repository) {
            $repository->attachTags($taggable->getMorphClass(), [$taggable->id, $taggable2->id], [$tagModel->id]);

            $this->assertCount(1, $taggable->fresh()->tags);
            $this->assertCount(1, $taggable2->fresh()->tags);
            $this->assertEquals($taggable->fresh()->tags->first()->id, $taggable2->fresh()->tags->first()->id);
        }
    }

    public function test_it_ignores_already_attached_tags()
    {
        $taggable = TaggableStub::create();
        $taggable2 = TaggableStub::create();
        $tagModel = $this->createTagModel();
        $tagModel2 = $this->createTagModel();

        foreach ($this->repositories() as $repository) {
            $repository->attachTags($taggable->getMorphClass(), [$taggable->id, $taggable2->id], [$tagModel->id]);

            $this->assertCount(1, $taggable->fresh()->tags);
            $this->assertCount(1, $taggable2->fresh()->tags);
            $this->assertEquals($taggable->fresh()->tags->first()->id, $taggable2->fresh()->tags->first()->id);

            $repository->attachTags($taggable->getMorphClass(), [$taggable->id, $taggable2->id], [$tagModel->id, $tagModel2->id]);

            $this->assertCount(2, $taggable->fresh()->tags);
            $this->assertCount(2, $taggable2->fresh()->tags);
            $this->assertEquals($tagModel->id, $taggable->fresh()->tags->first()->id);
            $this->assertEquals($tagModel2->id, $taggable->fresh()->tags[1]->id);
        }
    }

    public function test_it_can_detach_tags()
    {
        $taggable = TaggableStub::create();
        $taggable2 = TaggableStub::create();
        $tagModel = $this->createTagModel();

        foreach ($this->repositories() as $repository) {
            $repository->attachTags($taggable->getMorphClass(), [$taggable->id, $taggable2->id], [$tagModel->id]);

            $this->assertCount(1, $taggable->fresh()->tags);
            $this->assertCount(1, $taggable2->fresh()->tags);

            $repository->detachTags($taggable->getMorphClass(), [$taggable->id], [$tagModel->id]);

            $this->assertCount(0, $taggable->fresh()->tags);
            $this->assertCount(1, $taggable2->fresh()->tags);

        }
    }

    private function repositories(): Generator
    {
        yield new EloquentTaggableRepository();
    }
}
