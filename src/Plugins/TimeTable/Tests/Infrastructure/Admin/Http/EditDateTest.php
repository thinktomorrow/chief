<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\Admin\Http;

use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TestCase;

class EditDateTest extends TestCase
{
    public function test_it_can_visit_the_edit_date_page()
    {
        $timeTable = $this->createTimeTableModel();
        $date = $this->createDateModel([], [$timeTable->id]);

        $response = $this->asAdmin()->get(route('chief.timetable_dates.edit', [$timeTable->id, $date->id]));

        $response->assertStatus(200);
        $this->assertStringContainsString('action="'.route('chief.timetable_dates.update', $date->id).'"', $response->getContent());
        $this->assertStringContainsString('Hele dag gesloten', $response->getContent());
    }

    public function test_it_can_mark_a_date_as_closed()
    {
        $timeTable = $this->createTimeTableModel();
        $date = $this->createDateModel([], [$timeTable->id]);

        $this->performDateUpdate($date->id, [
            'closed' => ['1'],
            'timetables' => [$timeTable->id],
        ]);

        $date->refresh();

        $this->assertSame([], $date->slots);
        $this->assertEquals('halve speciale dag', $date->content);
        $this->assertEquals([$timeTable->id], $date->timetables()->pluck('timetables.id')->all());
    }
}
