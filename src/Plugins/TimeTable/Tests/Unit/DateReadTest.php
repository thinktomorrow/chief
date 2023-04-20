<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Unit;

use Thinktomorrow\Chief\Plugins\TimeTable\Application\Read\DateRead;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Hour;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Slot;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\DefaultDateRead;
use Thinktomorrow\Chief\Tests\TestCase;

class DateReadTest extends TestCase
{
    public function test_it_can_create_a_date()
    {
        $dateRead = $this->getInstance();

        $this->assertEquals('1', $dateRead->getId());
        $this->assertEquals(['1','2'], $dateRead->getTimeTableIds());
        $this->assertEquals([
            Slot::make(Hour::make(9), Hour::make(12)),
            Slot::make(Hour::make(13), Hour::make(16, 30)),
        ], $dateRead->getSlots());
    }

    public function test_it_can_read_data()
    {
        $dateRead = $this->getInstance(['data' => ['baz' => ['bad' => 'foobar']]]);

        $this->assertEquals(['bad' => 'foobar'], $dateRead->getData('baz'));
    }

    public function test_it_can_read_nested_data_with_dotted_syntax()
    {
        $dateRead = $this->getInstance(['data' => ['baz' => ['bad' => 'foobar']]]);

        $this->assertEquals('foobar', $dateRead->getData('baz.bad'));
        $this->assertEquals('foobar', $dateRead->getData('baz', 'bad'));
    }

    public function test_it_can_return_fallback_data()
    {
        $dateRead = $this->getInstance(['data' => ['baz' => ['bad' => 'foobar']]]);

        $this->assertEquals('FALLBACK', $dateRead->getData('unknown', null, 'FALLBACK'));
    }

    private function getInstance(array $values = [], array $timeTableIds = []): DateRead
    {
        return DefaultDateRead::fromMappedData(array_merge([
            'id' => '1',
            'timetable_id' => 'internal label',
            'date' => '2023-04-17',
            'slots' => json_encode([
                ['from' => '9:00', 'until' => '12:00'],
                ['from' => '13:00', 'until' => '16:30'],
            ]),
            'data' => [],
        ], $values), array_merge(['1', '2'], $timeTableIds));
    }
}
