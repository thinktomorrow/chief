<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure;

use Thinktomorrow\Chief\Plugins\TimeTable\Application\Read\DateRead;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Repositories\EloquentTaggableRepository;

class TaggableRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        TaggableStub::migrateUp();
    }

    public function test_it_can_sync_tags()
    {
        $taggable = TaggableStub::create();
        $tagModel = $this->createDateModel();

        foreach ($this->repositories() as $repository) {
            $repository->syncTags($taggable, [$tagModel->id]);

            $this->assertCount(1, $taggable->fresh()->tags);
            $this->assertInstanceOf(DateRead::class, $taggable->fresh()->getTags()->first());
        }
    }

    private function repositories(): \Generator
    {
        yield new EloquentTaggableRepository();
    }
}
