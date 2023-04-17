<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Tags\Crud;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Admin\Tags\Events\TagDeleted;
use Thinktomorrow\Chief\Admin\Tags\TagModel;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class DeleteTagTest extends ChiefTestCase
{
    use TagTestHelpers;

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
