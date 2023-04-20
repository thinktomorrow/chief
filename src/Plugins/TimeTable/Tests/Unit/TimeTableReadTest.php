<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Unit;

use Thinktomorrow\Chief\Plugins\TimeTable\Application\Read\TimeTableRead;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Slots;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\DefaultTimeTableRead;
use Thinktomorrow\Chief\Tests\TestCase;

class TimeTableReadTest extends TestCase
{
    public function test_it_can_create_a_week()
    {
        $weekRead = $this->getInstance();

        $this->assertEquals('1', $weekRead->getId());
        $this->assertEquals('internal label', $weekRead->getLabel());
    }

    public function test_it_can_get_slots_per_day()
    {
        $weekRead = $this->getInstance();

        $this->assertInstanceOf(Slots::class, $weekRead->getDays()[0]);
    }

    public function test_it_can_read_data()
    {
        $weekRead = $this->getInstance(['data' => ['baz' => ['bad' => 'foobar']]]);

        $this->assertEquals(['bad' => 'foobar'], $weekRead->getData('baz'));
    }

    public function test_it_can_read_nested_data_with_dotted_syntax()
    {
        $weekRead = $this->getInstance(['data' => ['baz' => ['bad' => 'foobar']]]);

        $this->assertEquals('foobar', $weekRead->getData('baz.bad'));
        $this->assertEquals('foobar', $weekRead->getData('baz', 'bad'));
    }

    public function test_it_can_return_fallback_data()
    {
        $weekRead = $this->getInstance(['data' => ['baz' => ['bad' => 'foobar']]]);

        $this->assertEquals('FALLBACK', $weekRead->getData('unknown', null, 'FALLBACK'));
    }

    private function getInstance(array $values = []): TimeTableRead
    {
        return DefaultTimeTableRead::fromMappedData(array_merge([
            'id' => '1',
            'label' => 'internal label',
            'data' => [],
        ], $values), [
            [
                'key' => 2,
                'slots' => json_encode([
                    ['from' => '9:00', 'until' => '12:00'],
                    ['from' => '13:00', 'until' => '16:30'],
                ]),
                'data' => ['foo' => 'bar'],
            ],
            [
                'key' => 1,
                'slots' => json_encode([
                    ['from' => '9:30', 'until' => '11:00'],
                    ['from' => '13:00', 'until' => '13:30'],
                    ['from' => '15:00', 'until' => '18:00'],
                ]),
                'data' => [],
            ],
            [
                'key' => 3,
                'slots' => json_encode([]),
                'data' => [],
            ],
        ]);
    }
}
