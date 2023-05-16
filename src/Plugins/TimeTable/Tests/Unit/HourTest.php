<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Exceptions\InvalidHourFormat;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Exceptions\InvalidMinutesFormat;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Hour;

class HourTest extends TestCase
{
    public function test_it_can_create_hour()
    {
        $hour = Hour::make(9, 30);

        $this->assertEquals(9, $hour->getHour());
        $this->assertEquals(30, $hour->getMinutes());
    }

    public function test_it_can_create_from_format()
    {
        $this->assertEquals('09:30', Hour::fromFormat('9:30')->getFormat());
        $this->assertEquals('09:30', Hour::fromFormat('09:30')->getFormat());
    }

    public function test_it_can_get_in_format()
    {
        $hour = Hour::make(9, 30);

        $this->assertEquals('09:30', $hour->getFormat()); // Default format
        $this->assertEquals('9:30', $hour->getFormat('g:i'));
        $this->assertEquals('09:30', $hour->getFormat('H:i'));
        $this->assertEquals('30 - 09', $hour->getFormat('i - H'));
    }

    public function test_it_can_create_hour_with_prepended_zero()
    {
        $hour = Hour::make(02, 05);

        $this->assertEquals(2, $hour->getHour());
        $this->assertEquals(5, $hour->getMinutes());
    }

    public function test_it_can_create_hour_without_minutes()
    {
        $hour = Hour::make(9);

        $this->assertEquals(9, $hour->getHour());
        $this->assertEquals(0, $hour->getMinutes());
    }

    public function test_it_cannot_create_invalid_hour()
    {
        $this->expectException(InvalidHourFormat::class);

        Hour::make(25);
    }

    public function test_it_cannot_create_invalid_hour_below_zero()
    {
        $this->expectException(InvalidHourFormat::class);

        Hour::make(-1);
    }

    public function test_it_cannot_create_invalid_minutes()
    {
        $this->expectException(InvalidMinutesFormat::class);

        Hour::make(10, 61);
    }

    public function test_it_cannot_create_invalid_minutes_below_zero()
    {
        $this->expectException(InvalidMinutesFormat::class);

        Hour::make(10, -5);
    }
}
