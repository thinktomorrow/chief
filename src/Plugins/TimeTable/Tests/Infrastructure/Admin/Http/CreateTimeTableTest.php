<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\Admin\Http;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\TimeTableCreated;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\TimeTableModel;
use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TestCase;

class CreateTimeTableTest extends TestCase
{
    public function test_it_can_visit_the_create_timetable()
    {
        $response = $this->asAdmin()->get(route('chief.timetables.create'));

        $response->assertStatus(200);
        $this->assertStringContainsString('action="'.route('chief.timetables.store').'"', $response->getContent());
    }

    public function test_guests_cannot_view_the_create_form()
    {
        $this->get(route('chief.timetables.create'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }

    public function test_it_can_create_a_timetable()
    {
        $this->performTimeTableStore();

        $this->assertEquals(1, TimeTableModel::count());

        $timetable = TimeTableModel::first();
        $this->assertEquals('Openingsuren Herenthout', $timetable->label);
    }

    public function test_it_can_validate_input()
    {
        $response = $this->performTimeTableStore(['label' => null]);

        $response->assertSessionHasErrors('label')
            ->assertStatus(302);

        $this->assertEquals(0, TimeTableModel::count());
    }

    public function test_it_emits_an_timetable_created_event()
    {
        Event::fake();

        $this->performTimeTableStore();

        Event::assertDispatched(TimeTableCreated::class);
    }
}
