<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Validation;

use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationNames;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;

class ValidationNamesTest extends FormsTestCase
{
    public function test_it_returns_basic_format()
    {
        $instance = ValidationNames::fromFormat('rule.key');

        $this->assertEquals(['rule.key'], $instance->get());
    }

    public function test_it_replaces_single_placeholder()
    {
        $instance = ValidationNames::fromFormat('field.:key')
            ->replace('key', ['name', 'email']);

        $this->assertEquals(['field.name', 'field.email'], $instance->get());
    }

    public function test_it_replaces_multiple_placeholders()
    {
        $instance = ValidationNames::fromFormat('field.:locale.:key')
            ->replace('locale', ['nl', 'en'])
            ->replace('key', ['title', 'description']);

        $this->assertEquals([
            'field.nl.title',
            'field.nl.description',
            'field.en.title',
            'field.en.description',
        ], $instance->get());
    }

    public function test_it_keeps_format_when_placeholder_is_empty()
    {
        $instance = ValidationNames::fromFormat('field.:key')
            ->replace('key', []);

        $this->assertEquals(['field.:key'], $instance->get());
    }

    public function test_it_removes_keys_for_empty_translations_except_required_locale()
    {
        $instance = ValidationNames::fromFormat('trans.:locale.:key')
            ->replace('locale', ['en', 'fr'])
            ->replace('key', ['title', 'subtitle'])
            ->payload([
                'trans' => [
                    'en' => ['title' => '', 'subtitle' => ''], // required
                    'fr' => ['title' => '', 'subtitle' => null], // will be removed
                ],
            ])
            ->requiredLocale('en');

        $this->assertEquals([
            'trans.en.title',
            'trans.en.subtitle',
        ], $instance->get());
    }

    public function test_it_keeps_translations_for_required_locale_even_when_empty()
    {
        $instance = ValidationNames::fromFormat('trans.:locale.title')
            ->replace('locale', ['nl', 'fr'])
            ->payload([
                'trans' => [
                    'nl' => ['title' => null],
                    'fr' => ['title' => null],
                ],
            ])
            ->requiredLocale('nl');

        $this->assertEquals([
            'trans.nl.title',
        ], $instance->get());
    }

    public function test_it_removes_keys_marked_for_removal()
    {
        $instance = ValidationNames::fromFormat('files.:key')
            ->replace('key', ['foo', 'bar.remove', 'baz'])
            ->removeKeysContaining(['.remove']);

        $this->assertEquals(['files.foo', 'files.baz'], $instance->get());
    }

    public function test_it_uses_wildcard_in_keys_to_be_removed()
    {
        $instance = ValidationNames::fromFormat(':key')
            ->replace('key', ['foo', 'bar', 'bor', 'bir'])
            ->removeKeysContaining(['b*r']);

        $this->assertEquals(['foo'], $instance->get());
    }

    public function test_it_removes_empty_localized_file_entries_excluding_required_locale()
    {
        $instance = ValidationNames::fromFormat('images.foo.nl')
            ->payload([
                'images' => [
                    'foo' => [
                        'nl' => '', // empty and not required
                        'en' => 'somefile.jpg',
                    ],
                ],
            ])
            ->requiredLocale('en');

        $this->assertEquals([], $instance->get());
    }

    public function test_it_does_not_remove_required_locale_file_entry_even_if_empty()
    {
        $instance = ValidationNames::fromFormat('images.foo.en')
            ->payload([
                'images' => [
                    'foo' => [
                        'en' => '',
                    ],
                ],
            ])
            ->requiredLocale('en');

        $this->assertEquals(['images.foo.en'], $instance->get());
    }

    public function test_it_does_nothing_if_payload_is_empty()
    {
        $instance = ValidationNames::fromFormat('field.key')
            ->payload([]);

        $this->assertEquals(['field.key'], $instance->get());
    }

    public function test_it_does_nothing_if_keys_to_remove_are_empty()
    {
        $instance = ValidationNames::fromFormat('field.key')
            ->removeKeysContaining([]);

        $this->assertEquals(['field.key'], $instance->get());
    }
}
