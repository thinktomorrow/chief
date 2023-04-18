<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Tests\Infrastructure\Admin\Http;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Events\DateDeleted;
use Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Models\DateModel;
use Thinktomorrow\Chief\Plugins\WeekTable\Tests\Infrastructure\TestCase;

class DeleteTagTest extends TestCase
{
    public function test_it_can_delete_a_tag()
    {
        $model = $this->createTagModel();
        $this->performTagDelete($model->id);

        $this->assertEquals(0, DateModel::count());
        $this->assertNull(DateModel::find($model->id));
    }

    public function test_it_emits_an_tag_deleted_event()
    {
        Event::fake();

        $model = $this->createTagModel();
        $this->performTagDelete($model->id);

        Event::assertDispatched(DateDeleted::class);
    }
}
