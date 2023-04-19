<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\Admin\Http;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\TimeTableDeleted;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\TimeTableModel;
use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TestCase;

class DeleteTagGroupTest extends TestCase
{
    public function test_it_can_delete_a_group()
    {
        $model = $this->createTimeTableModel();
        $this->performTimeTableDelete($model->id);

        $this->assertEquals(0, TimeTableModel::count());
        $this->assertNull(TimeTableModel::find($model->id));
    }

    public function test_it_emits_an_tag_deleted_event()
    {
        Event::fake();

        $model = $this->createTimeTableModel();
        $this->performTimeTableDelete($model->id);

        Event::assertDispatched(TimeTableDeleted::class);
    }
}
