<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Export;

use Thinktomorrow\Chief\Plugins\Export\Export\Lines\FieldLine;
use Thinktomorrow\Chief\Tests\TestCase;

class LineTest extends TestCase
{
    private FieldLine $line;

    public function setUp(): void
    {
        parent::setUp();

        $this->line = $this->createLine();
    }

    public function test_it_has_encrypted_reference()
    {
        $this->assertEquals('modelReference|fieldKey', decrypt($this->line->getReference()));
    }

    public function test_it_can_get_value_for_specific_locale()
    {
        $this->assertEquals(['nl' => 'nl value', 'en' => 'en value'], $this->line->getValues());
        $this->assertEquals('nl value', $this->line->getValue('nl'));
        $this->assertEquals('en value', $this->line->getValue('en'));
        $this->assertEquals(null, $this->line->getValue());
        $this->assertEquals(null, $this->line->getValue('xx'));
    }

    public function test_it_can_get_value_for_non_localized_value()
    {
        $line = $this->createLine(['x' => 'non-localized value', 'nl' => 'nl value']);
        $this->assertEquals(['x' => 'non-localized value', 'nl' => 'nl value'], $line->getValues());
        $this->assertEquals('nl value', $line->getValue('nl'));
        $this->assertEquals('non-localized value', $line->getValue());
        $this->assertEquals('non-localized value', $line->getValue('x'));
    }

    public function test_it_can_transpose_to_array()
    {
        $this->assertEquals($this->line->getColumns(), $this->line->toArray());
        $this->assertEquals([$this->line->getReference(), 'resourceLabel', 'modelLabel', 'fieldLabel', 'nl value', 'en value', ''], $this->line->getColumns());
    }

    public function test_it_can_get_remarks()
    {
        $line = $this->createLine(['nl' => 'with <strong>html</strong>']);
        $this->assertEquals('html', $line->getRemarks());

        $line = $this->createLine(['nl' => 'with :placeholder']);
        $this->assertEquals('placeholder', $line->getRemarks());

        $line = $this->createLine(['nl' => 'with <button href="#link"></button>']);
        $this->assertEquals('html, link', $line->getRemarks());
    }

    private function createLine(array $values = []): FieldLine
    {
        return new FieldLine(
            'modelReference',
            'fieldKey',
            'resourceLabel',
            'modelLabel',
            'fieldLabel',
            $values ?: ['nl' => 'nl value', 'en' => 'en value']
        );
    }
}
