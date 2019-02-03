<?php

namespace Thinktomorrow\Chief\Tests\Feature\Common;

use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\FieldType;
use Thinktomorrow\Chief\Fields\Types\HtmlField;
use Thinktomorrow\Chief\Fields\Types\InputField;
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
    public function by_default_label_is_key_and_description_null()
    {
        $field = InputField::make('foobar');

        $this->assertEquals('foobar', $field->label);
        $this->assertNull($field->description);

        $this->assertNull($field->label(null)->label);
    }

    /** @test */
    public function it_can_make_a_field_for_html_text()
    {
        $field = HtmlField::make('foobar');

        $this->assertInstanceOf(Field::class, $field);
        $this->assertEquals(FieldType::HTML, $field->type);
    }
}
