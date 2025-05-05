<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\Admin\Http;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\TimeTableUpdated;
use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TestCase;

class EditTimeTableTest extends TestCase
{
    public function test_it_can_visit_the_edit_timetable()
    {
        $model = $this->createTimeTableModel();

        $response = $this->asAdmin()->get(route('chief.timetables.edit', $model->id));

        $response->assertStatus(200);
        $this->assertStringContainsString('action="'.route('chief.timetables.update', $model->id).'"', $response->getContent());
        $this->assertStringContainsString('value="Openingsuren Herenthout"', $response->getContent());
    }

    public function test_guests_cannot_view_the_edit_form()
    {
        $model = $this->createTimeTableModel();

        $this->get(route('chief.timetables.edit', $model->id))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }

    public function test_it_can_update_a_timetable()
    {
        $model = $this->createTimeTableModel();
        $this->performTimeTableUpdate($model->id);

        $model->refresh();

        $this->assertEquals('Openingsuren Herentals', $model->label);
    }

    public function test_it_emits_an_timetable_updated_event()
    {
        Event::fake();

        $model = $this->createTimeTableModel();
        $this->performTimeTableUpdate($model->id);

        Event::assertDispatched(TimeTableUpdated::class);
    }
}
