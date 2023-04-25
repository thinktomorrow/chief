<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\App\Http;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\DateDeleted;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\DateModel;
use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TestCase;

class DeleteTagTest extends TestCase
{
    public function test_it_can_delete_a_tag()
    {
        $model = $this->createDateModel();
        $this->performDateDelete($model->id);

        $this->assertEquals(0, DateModel::count());
        $this->assertNull(DateModel::find($model->id));
    }

    public function test_it_emits_an_tag_deleted_event()
    {
        Event::fake();

        $model = $this->createDateModel();
        $this->performDateDelete($model->id);

        Event::assertDispatched(DateDeleted::class);
    }
}
