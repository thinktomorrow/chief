<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure;

use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagRead;
use Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Repositories\EloquentTaggableRepository;

class TaggableRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        HasWeekTableStub::migrateUp();
    }

    public function test_it_can_sync_tags()
    {
        $taggable = HasWeekTableStub::create();
        $tagModel = $this->createTagModel();

        foreach ($this->repositories() as $repository) {
            $repository->syncTags($taggable, [$tagModel->id]);

            $this->assertCount(1, $taggable->fresh()->tags);
            $this->assertInstanceOf(TagRead::class, $taggable->fresh()->getTags()->first());
        }
    }

    private function repositories(): \Generator
    {
        yield new EloquentTaggableRepository();
    }
}
