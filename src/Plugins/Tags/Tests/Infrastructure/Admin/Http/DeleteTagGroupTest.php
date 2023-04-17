<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure\Admin\Http;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Events\TagGroupDeleted;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagGroupModel;
use Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure\TestCase;

class DeleteTagGroupTest extends TestCase
{
    public function test_it_can_delete_a_group()
    {
        $model = $this->createTaggroupModel();
        $this->performTagGroupDelete($model->id);

        $this->assertEquals(0, TagGroupModel::count());
        $this->assertNull(TagGroupModel::find($model->id));
    }

    public function test_it_emits_an_tag_deleted_event()
    {
        Event::fake();

        $model = $this->createTaggroupModel();
        $this->performTagGroupDelete($model->id);

        Event::assertDispatched(TagGroupDeleted::class);
    }
}
