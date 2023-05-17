<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Hour;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Slot;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\SlotsByDay;

class SlotsByDayTest extends TestCase
{
    public function test_it_can_create_slots()
    {
        $slotsByDay = SlotsByDay::fromMappedData(2, [
            ['from' => '10:00', 'until' => '12:45'],
            ['from' => '13:00', 'until' => '17:00'],
            ['from' => '18:30', 'until' => '23:00'],
        ]);

        $this->assertEquals([
            Slot::make(Hour::make('10'), Hour::make('12','45')),
            Slot::make(Hour::make('13'), Hour::make('17')),
            Slot::make(Hour::make('18', '30'), Hour::make('23','00')),
        ], $slotsByDay->getSlots());

        $this->assertEquals(Day::make(2, 'Dinsdag'), $slotsByDay->getDay());
    }

    public function test_it_ignores_empty_slots()
    {
        $slotsByDay = SlotsByDay::fromMappedData(2, [
            ['from' => '10:00', 'until' => '12:45'],
            ['from' => null, 'until' => null],
            ['from' => '18:30', 'until' => '23:00'],
        ]);

        $this->assertEquals([
            Slot::make(Hour::make('10'), Hour::make('12','45')),
            Slot::make(Hour::make('18', '30'), Hour::make('23','00')),
        ], $slotsByDay->getSlots());
    }

    public function test_it_merges_open_end_hour()
    {
        $slotsByDay = SlotsByDay::fromMappedData(2, [
            ['from' => '10:00', 'until' => '12:45'],
            ['from' => '13:00', 'until' => null],
            ['from' => '18:30', 'until' => '23:00'],
        ]);

        $this->assertEquals([
            Slot::make(Hour::make('10'), Hour::make('12','45')),
            Slot::make(Hour::make('13'), Hour::make('23','00')),
        ], $slotsByDay->getSlots());
    }

    public function test_it_merges_open_start_hour()
    {
        $slotsByDay = SlotsByDay::fromMappedData(2, [
            ['from' => '10:00', 'until' => '12:45'],
            ['from' => null, 'until' => '17:00'],
            ['from' => '18:30', 'until' => '23:00'],
        ]);

        $this->assertEquals([
            Slot::make(Hour::make('10'), Hour::make('17')),
            Slot::make(Hour::make('18', '30'), Hour::make('23','00')),
        ], $slotsByDay->getSlots());
    }

    public function test_it_keep_open_start_hour_if_only_one_slot_is_given()
    {
        $slotsByDay = SlotsByDay::fromMappedData(2, [
            ['from' => null, 'until' => '10:00'],
        ]);

        $this->assertEquals([
            Slot::make(null, Hour::make('10')),
        ], $slotsByDay->getSlots());
    }

    public function test_it_keep_open_end_hour_if_only_one_slot_is_given()
    {
        $slotsByDay = SlotsByDay::fromMappedData(2, [
            ['from' => '10:00', 'until' => null],
        ]);

        $this->assertEquals([
            Slot::make(Hour::make('10'), null),
        ], $slotsByDay->getSlots());
    }

    public function test_it_combines_slots_with_overlap()
    {
        $slotsByDay = SlotsByDay::fromMappedData(2, [
            ['from' => '10:00', 'until' => '12:45'],
            ['from' => '11:00', 'until' => '17:00'],
        ]);

        $this->assertEquals([
            Slot::make(Hour::make('10'), Hour::make('17')),
        ], $slotsByDay->getSlots());
    }
}
