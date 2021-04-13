<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Thinktomorrow\Chief\ManagedModels\Fields\Field;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\AbstractField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\FieldType;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\TestCase;

class FieldNameTest extends TestCase
{
    /** @test */
    public function it_uses_the_key_as_name()
    {
        $this->assertEquals('title', InputField::make('title')->getName());
        $this->assertEquals('title[foobar]', InputField::make('title[foobar]')->getName());
        $this->assertEquals('title[foobar]', InputField::make('title.foobar')->getName());
    }

    /** @test */
    public function when_localized_it_uses_a_localized_format_for_the_name()
    {
        $field = InputField::make('title')
                    ->locales(['nl','en']);

        $this->assertEquals('title', $field->getName());
        $this->assertEquals('trans[nl][title]', $field->getName('nl'));
        $this->assertEquals('trans[en][title]', $field->getName('en'));
    }

    /** @test */
    public function when_name_is_set_explicitly_this_is_used_instead_of_key()
    {
        $field = InputField::make('title')->name('custom-title');

        $this->assertEquals('custom-title', $field->getName());
    }

    /** @test */
    public function a_custom_name_is_used_as_localized_format_when_it_contains_a_locale_placeholder()
    {
        $field = InputField::make('title')
            ->locales(['nl','en'])
            ->name('custom-title-:locale');

        $this->assertEquals('custom-title-:locale', $field->getName());
        $this->assertEquals('custom-title-nl', $field->getName('nl'));
        $this->assertEquals('custom-title-en', $field->getName('en'));
    }

    /** @test */
    public function when_localized_format_and_name_are_both_set_the_localized_format_is_used_when_name_lacks_a_locale_placeholder()
    {
        $field = InputField::make('title')
            ->locales(['nl','en'])
            ->name('custom-title');

        $this->assertEquals('custom-title', $field->getName());
        $this->assertEquals('trans[nl][custom-title]', $field->getName('nl'));
        $this->assertEquals('trans[en][custom-title]', $field->getName('en'));
    }

    /** @test */
    public function it_uses_the_name_for_the_validation()
    {
        $this->assertEquals(['title'], InputField::make('title')->getValidationNames());
        $this->assertEquals(['title.new'], InputField::make('title[new]')->getValidationNames());
        $this->assertEquals(['title.new'], InputField::make('title.new')->getValidationNames());
        $this->assertEquals(['custom-title'], InputField::make('title')->name('custom-title')->getValidationNames());
        $this->assertEquals(['title.new'], InputField::make('title[new]')->getValidationNames());

        // Localised values
        // e.g. when name contains :locale this is used else the localizedFormat is used...
        $this->assertEquals(['trans.nl.title', 'trans.en.title'], InputField::make('title')->locales(['nl','en'])->getValidationNames());
        $this->assertEquals(['title.nl', 'title.en'], InputField::make('title.:locale')->locales(['nl','en'])->getValidationNames());
    }

    /** @test */
    public function it_can_change_the_localized_format_per_field()
    {
        $customField = new class(FieldType::fromString(FieldType::TEXT), 'title') extends AbstractField implements Field {
            protected function getLocalizedNameFormat(): string
            {
                return 'foo.:locale.:name';
            }
        };

        $field = $customField->locales(['nl','en']);

        $this->assertEquals('title', $field->getName());
        $this->assertEquals('foo[nl][title]', $field->getName('nl'));
        $this->assertEquals('foo[en][title]', $field->getName('en'));
    }
}
