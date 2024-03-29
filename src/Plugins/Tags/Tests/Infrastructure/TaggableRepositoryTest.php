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

        foreach ($this->repositories() as $repository) {
            $repository->syncTags($taggable, [$tagModel->id]);

            $this->assertCount(1, $taggable->fresh()->tags);
            $this->assertInstanceOf(TagRead::class, $taggable->fresh()->getTags()->first());
        }
    }

    private function repositories(): Generator
    {
        yield new EloquentTaggableRepository();
    }
}
