<?php

namespace Thinktomorrow\Chief\Tests\Unit\Common;

use Thinktomorrow\Chief\Common\Fields\Field;
use Thinktomorrow\Chief\Common\Fields\FieldType;
use Thinktomorrow\Chief\Common\Fields\HtmlField;
use Thinktomorrow\Chief\Common\Fields\InputField;
use Thinktomorrow\Chief\Tests\TestCase;

class TranslatableFieldTest extends TestCase
{
    /** @test */
    public function it_can_make_a_field_for_input_text()
    {
        $field = InputField::make('foobar');

        $this->assertInstanceOf(Field::class, $field);
        $this->assertEquals(FieldType::INPUT, $field->type);
    }

    /** @test */
    public function it_can_add_optional_label_and_description()
    {
        $field = InputField::make('label')->label('label')->description('description');

        $this->assertEquals('label', $field->label);
        $this->assertEquals('description', $field->description);
    }

    /** @test */
    public function non_given_label_and_description_are_by_default_null()
    {
        $field = InputField::make('foobar');

        $this->assertNull($field->label);
        $this->assertNull($field->description);
    }

    /** @test */
    public function it_can_make_a_field_for_html_text()
    {
        $field = HtmlField::make('foobar');

        $this->assertInstanceOf(Field::class, $field);
        $this->assertEquals(FieldType::HTML, $field->type);
    }
}
