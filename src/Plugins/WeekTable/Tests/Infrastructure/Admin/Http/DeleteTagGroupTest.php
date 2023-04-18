<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Tests\Infrastructure\Admin\Http;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Events\WeekTableDeleted;
use Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Models\WeekTableModel;
use Thinktomorrow\Chief\Plugins\WeekTable\Tests\Infrastructure\TestCase;

class DeleteTagGroupTest extends TestCase
{
    public function test_it_can_delete_a_group()
    {
        $model = $this->createTaggroupModel();
        $this->performTagGroupDelete($model->id);

        $this->assertEquals(0, WeekTableModel::count());
        $this->assertNull(WeekTableModel::find($model->id));
    }

    public function test_it_emits_an_tag_deleted_event()
    {
        Event::fake();

        $model = $this->createTaggroupModel();
        $this->performTagGroupDelete($model->id);

        Event::assertDispatched(WeekTableDeleted::class);
    }
}
