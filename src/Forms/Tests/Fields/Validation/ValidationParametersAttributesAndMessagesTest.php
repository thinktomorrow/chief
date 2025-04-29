<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Validation;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;

class ValidationParametersAttributesAndMessagesTest extends FormsTestCase
{
    public function test_it_can_create_the_attributes_array()
    {
        $field = Text::make('xxx')->rules('email');

        $this->assertEquals(
            ['xxx' => 'xxx'],
            ValidationParameters::make($field)->getAttributes()
        );
    }

    public function test_it_can_create_a_custom_attributes_array()
    {
        $field = Text::make('xxx')->validationAttribute('foobar')->rules('email');

        $this->assertEquals(
            ['xxx' => 'foobar'],
            ValidationParameters::make($field)->getAttributes()
        );
    }

    public function test_it_can_create_the_localized_attributes_array()
    {
        $field = Text::make('xxx')->locales(['nl', 'en'])->rules('email');

        $this->assertEquals(
            [
                'xxx.nl' => 'nl xxx',
                'xxx.en' => 'en xxx',
            ],
            ValidationParameters::make($field)->getAttributes()
        );
    }

    public function test_file_with_one_locale_shows_attribute_without_locale()
    {
        $field = Text::make('xxx')->locales(['nl'])->rules('email');

        $this->assertEquals(
            [
                'xxx.nl' => 'xxx',
            ],
            ValidationParameters::make($field)->getAttributes()
        );
    }

    public function test_it_can_create_a_custom_localized_attributes_array()
    {
        $field = Text::make('xxx')->validationAttribute('foobar')->rules('email');

        $this->assertEquals(
            ['xxx' => 'foobar'],
            ValidationParameters::make($field)->getAttributes()
        );
    }

    public function test_it_can_create_the_messages_array()
    {
        $field = Text::make('xxx')->locales(['nl', 'en'])->validationMessages(['required' => ':locale is verplicht']);

        $this->assertEquals(
            [
                'xxx.nl.required' => 'nl is verplicht',
                'xxx.en.required' => 'en is verplicht',
            ],
            ValidationParameters::make($field)->getMessages()
        );
    }

    public function test_it_returns_custom_messages_as_is_when_keyed()
    {
        $messages = ['xxx.nl.required' => 'is verplicht'];
        $field = Text::make('xxx')->locales(['nl', 'en'])->validationMessages($messages);

        $this->assertEquals(
            $messages,
            ValidationParameters::make($field)->getMessages()
        );
    }

    public function test_it_returns_value_as_is_when_array_is_already_keyed()
    {
        $messages = ['custom.key' => 'value'];
        $field = Text::make('xxx')->validationMessages($messages);

        $this->assertEquals(
            $messages,
            ValidationParameters::make($field)->getMessages()
        );
    }

    public function test_it_handles_locale_replacements_in_custom_messages()
    {
        $field = Text::make('xxx')->locales(['nl', 'fr'])->validationMessages(['min' => 'Minimaal :locale waarde']);

        $this->assertEquals(
            [
                'xxx.nl.min' => 'Minimaal nl waarde',
                'xxx.fr.min' => 'Minimaal fr waarde',
            ],
            ValidationParameters::make($field)->getMessages()
        );
    }
}
