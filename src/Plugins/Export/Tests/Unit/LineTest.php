<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Unit;

use Thinktomorrow\Chief\Plugins\Export\Export\Lines\FieldLine;
use Thinktomorrow\Chief\Tests\TestCase;

class LineTest extends TestCase
{
    public function test_it_has_encrypted_reference()
    {
        $line = new FieldLine(
            'modelReference',
            'fieldKey',
            'modelLabel',
            'fieldLabel',
            'originalValue',
            ['nl' => 'targetValue']
        );

        $this->assertEquals('modelReference|fieldKey', decrypt($line->getReference()));
    }

    public function test_it_can_transpose_to_array()
    {
        $line = new FieldLine(
            'modelReference',
            'fieldKey',
            'modelLabel',
            'fieldLabel',
            'originalValue',
            ['nl' => 'targetValue']
        );

        $this->assertEquals(['', $line->getReference(), 'modelLabel', 'fieldLabel', 'originalValue', 'targetValue'], $line->getColumns());
        $this->assertEquals($line->getColumns(), $line->toArray());
    }
}
