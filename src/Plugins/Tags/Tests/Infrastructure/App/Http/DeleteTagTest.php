<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure\App\Http;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Events\TagDeleted;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagModel;
use Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure\TestCase;

class DeleteTagTest extends TestCase
{
    public function test_it_can_delete_a_tag()
    {
        $model = $this->createTagModel();
        $this->performTagDelete($model->id);

        $this->assertEquals(0, TagModel::count());
        $this->assertNull(TagModel::find($model->id));
    }

    public function test_it_emits_an_tag_deleted_event()
    {
        Event::fake();

        $model = $this->createTagModel();
        $this->performTagDelete($model->id);

        Event::assertDispatched(TagDeleted::class);
    }
}
