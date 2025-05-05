<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\Admin\Http;

use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TestCase;

class IndexTimeTableTest extends TestCase
{
    public function test_it_can_visit_the_index()
    {
        $model = $this->createTimeTableModel();

        $response = $this->asAdmin()->get(route('chief.timetables.index'));

        $response->assertStatus(200);
    }

    public function test_guests_cannot_view_the_index_form()
    {
        $model = $this->createTimeTableModel();

        $this->get(route('chief.timetables.index'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
