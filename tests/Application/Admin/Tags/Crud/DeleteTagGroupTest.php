<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Tags\Crud;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Admin\Tags\Events\TagGroupDeleted;
use Thinktomorrow\Chief\Admin\Tags\TagGroupModel;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class DeleteTagGroupTest extends ChiefTestCase
{
    use TagTestHelpers;

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
