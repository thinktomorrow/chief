<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Number;
use Thinktomorrow\Chief\Forms\Fields\Slider;
use Thinktomorrow\Chief\Forms\Fields\Hidden;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Tests\Unit\Forms\TestCase;

class FieldAttributesTest extends TestCase
{
    private array $classes;

    public function setUp(): void
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
        ];
    }

    /** @test */
    public function it_has_default_attributes()
    {
        foreach ($this->classes as $class) {

            /** @var Field $component */
            $component = $class::make('xxx');

            $this->assertEquals('xxx', $component->getKey());
            $this->assertEquals('xxx', $component->getId());
            $this->assertEquals('xxx', $component->getName());
            $this->assertEquals('xxx', $component->getColumnName());
        }
    }

    /** @test */
    public function it_can_use_key_with_brackets()
    {
        $component = Text::make('form[title]');

        $this->assertEquals('form[title]', $component->getKey());
        $this->assertEquals('form.title', $component->getId());
        $this->assertEquals('form[title]', $component->getName());
        $this->assertEquals('form[title]', $component->getColumnName());
    }

    /** @test */
    public function when_localized_it_uses_a_localized_format_for_the_name()
    {
        $component = Text::make('title')->locales(['nl', 'en']);

        $this->assertEquals('title', $component->getId());
        $this->assertEquals('title', $component->getName());
        $this->assertEquals('trans[nl][title]', $component->getName('nl'));
        $this->assertEquals('trans[en][title]', $component->getName('en'));
        $this->assertEquals('title', $component->getColumnName());
    }

    /** @test */
    public function when_files_are_localized_a_specific_localized_format_is_used()
    {
        $component = File::make('image')->locales(['nl', 'en']);

        $this->assertEquals('image', $component->getId());
        $this->assertEquals('image', $component->getName());
        $this->assertEquals('files[image][nl]', $component->getName('nl'));
        $this->assertEquals('files[image][en]', $component->getName('en'));
        $this->assertEquals('image', $component->getColumnName());
    }

    /** @test */
    public function a_custom_name_is_used_as_localized_format_when_it_contains_a_locale_placeholder()
    {
        $field = Text::make('title')
            ->locales(['nl','en'])
            ->name('custom-title-:locale');

        $this->assertEquals('custom-title-:locale', $field->getName());
        $this->assertEquals('custom-title-nl', $field->getName('nl'));
        $this->assertEquals('custom-title-en', $field->getName('en'));
    }

    /** @test */
    public function custom_name_is_used_for_localized_name()
    {
        $field = Text::make('title')
            ->locales(['nl','en'])
            ->name('custom-title');

        $this->assertEquals('custom-title', $field->getName());
        $this->assertEquals('trans[nl][custom-title]', $field->getName('nl'));
        $this->assertEquals('trans[en][custom-title]', $field->getName('en'));
    }
}
