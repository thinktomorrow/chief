<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields;

use Thinktomorrow\Chief\Forms\Fields\Date;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Hidden;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Number;
use Thinktomorrow\Chief\Forms\Fields\Slider;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Forms\Fields\Time;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;

class FieldAttributesFormsTest extends FormsTestCase
{
    private array $classes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classes = [
            Textarea::class,
            Text::class,
            Number::class,
            Slider::class,
            File::class,
            Image::class,
            Hidden::class,
            Date::class,
            Time::class,
        ];
    }

    public function test_it_has_default_attributes()
    {
        foreach ($this->classes as $class) {
            /** @var Field $component */
            $component = $class::make('xxx');

            $this->assertEquals('xxx', $component->getKey());
            $this->assertEquals('xxx', $component->getId());
            $this->assertEquals('xxx', $component->getColumnName());

            if ($component instanceof File) {
                $this->assertEquals('files[xxx]', $component->getName());
            } else {
                $this->assertEquals('xxx', $component->getName());
            }
        }
    }

    public function test_it_can_use_key_with_brackets()
    {
        $component = Text::make('form[title]');

        $this->assertEquals('form[title]', $component->getKey());
        $this->assertEquals('form.title', $component->getId());
        $this->assertEquals('form[title]', $component->getName());
        $this->assertEquals('form[title]', $component->getColumnName());
    }
}
