<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Tests\Unit;

use Thinktomorrow\Chief\Plugins\WeekTable\Application\Read\DateRead;
use Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Models\DefaultDateRead;
use Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Models\WeekTableModel;
use Thinktomorrow\Chief\Tests\TestCase;

class DateReadTest extends TestCase
{
    public function test_it_can_create_a_date()
    {
        $dateRead = $this->getInstance();

        $this->assertEquals('1', $dateRead->getId());
        $this->assertEquals(['1','2'], $dateRead->getWeekTableIds());
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

    private function getInstance(array $values = [], array $weekTableIds = []): DateRead
    {
        return DefaultDateRead::fromMappedData(array_merge([
            'id' => '1',
            'weektable_id' => 'internal label',
            'date' => '2023-04-17',
            'periods' => json_encode([
                ['from' => '9:00', 'until' => '12:00'],
                ['from' => '13:00', 'until' => '16:30'],
            ]),
            'data' => [],
        ], $values), [
            WeekTableModel::class => array_merge(['1', '2'], $weekTableIds),
        ]);
    }
}
