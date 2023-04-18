<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Tests\Unit;

use Thinktomorrow\Chief\Plugins\WeekTable\Application\Read\DateRead;
use Thinktomorrow\Chief\Plugins\WeekTable\Application\Read\WeekTableRead;
use Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Models\DefaultDateRead;
use Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Models\DefaultWeekTableRead;
use Thinktomorrow\Chief\Tests\TestCase;

class WeekTableReadTest extends TestCase
{
    public function test_it_can_create_a_week()
    {
        $weekRead = $this->getInstance();

        $this->assertEquals('1', $weekRead->getId());
        $this->assertEquals('internal label', $weekRead->getLabel());
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

    private function getInstance(array $values = []): WeekTableRead
    {
        return DefaultWeekTableRead::fromMappedData(array_merge([
            'id' => '1',
            'label' => 'internal label',
            'data' => [],
        ], $values));
    }
}
