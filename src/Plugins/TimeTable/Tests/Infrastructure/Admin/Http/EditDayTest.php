<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\Admin\Http;

use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TestCase;

class EditDayTest extends TestCase
{
    public function test_it_can_visit_the_edit_day_page()
    {
        $timeTable = $this->createTimeTableModel();
        $day = $this->createDayModel($timeTable->id);

        $response = $this->asAdmin()->get(route('chief.timetable_days.edit', $day->id));

        $response->assertStatus(200);
        $this->assertStringContainsString('action="'.route('chief.timetable_days.update', $day->id).'"', $response->getContent());
        $this->assertStringContainsString('Hele dag gesloten', $response->getContent());
    }

    public function test_it_can_mark_a_day_as_closed()
    {
        $timeTable = $this->createTimeTableModel();
        $day = $this->createDayModel($timeTable->id);

        $this->performDayUpdate($day->id, [
            'closed' => ['1'],
        ]);

        $day->refresh();

        $this->assertSame([], $day->slots);
        $this->assertEquals('halve speciale dag', $day->content);
    }
}
