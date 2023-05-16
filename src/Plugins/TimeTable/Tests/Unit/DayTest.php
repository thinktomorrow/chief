<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Exceptions\InvalidDayFormat;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day;

class DayTest extends TestCase
{
    public function test_it_can_create_day()
    {
        $day = Day::make(7, 'label');

        $this->assertEquals(7, $day->getIso8601WeekDay());
        $this->assertEquals('label', $day->getLabel());
    }

    public function test_it_can_create_from_iso_format()
    {
        $this->assertEquals('1', Day::fromIso8601Format('1')->getIso8601WeekDay());
        $this->assertEquals('Dinsdag', Day::fromIso8601Format('2')->getLabel());
    }

    public function test_it_can_create_from_datetime()
    {
        $now = now()->startOfWeek();

        $this->assertEquals($now->format('N'), Day::fromDateTime($now)->getIso8601WeekDay());
        $this->assertEquals('Maandag', Day::fromDateTime($now)->getLabel());
    }

    public function test_it_can_create_from_datetime_string()
    {
        $now = now()->startOfWeek();

        $this->assertEquals($now->format('N'), Day::fromDateTime($now->format('Y-m-d H:i:s'))->getIso8601WeekDay());
        $this->assertEquals('Maandag', Day::fromDateTime($now->format('Y-m-d H:i:s'))->getLabel());
    }

    public function test_it_can_get_labels_for_each_day()
    {
        $this->assertEquals('Maandag', Day::fromIso8601Format('1')->getLabel());
        $this->assertEquals('Dinsdag', Day::fromIso8601Format('2')->getLabel());
        $this->assertEquals('Woensdag', Day::fromIso8601Format('3')->getLabel());
        $this->assertEquals('Donderdag', Day::fromIso8601Format('4')->getLabel());
        $this->assertEquals('Vrijdag', Day::fromIso8601Format('5')->getLabel());
        $this->assertEquals('Zaterdag', Day::fromIso8601Format('6')->getLabel());
        $this->assertEquals('Zondag', Day::fromIso8601Format('7')->getLabel());
    }

    public function test_it_cannot_create_invalid_day()
    {
        $this->expectException(InvalidDayFormat::class);

        Day::make(8, 'unknown');
    }

    public function test_it_cannot_create_invalid_day_below_zero()
    {
        $this->expectException(InvalidDayFormat::class);

        Day::make(-1, 'unknown');
    }
}
