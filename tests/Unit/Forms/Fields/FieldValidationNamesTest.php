<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationNames;
use Thinktomorrow\Chief\Tests\TestCase;

class FieldValidationNamesTest extends TestCase
{
    /** @test */
    public function it_can_get_the_rules()
    {
        $instance = ValidationNames::fromFormat('rulekey');

        $this->assertEquals(['rulekey'], $instance->get());
    }

    /** @test */
    public function it_can_replace_placeholder_value_in_the_key()
    {
        $instance = ValidationNames::fromFormat(':key')
                                        ->replace('key', ['foo','bar']);

        $this->assertEquals([
            'foo',
            'bar',
        ], $instance->get());
    }

    /** @test */
    public function it_can_replace_multiple_placeholder_values_in_the_key()
    {
        $instance = ValidationNames::fromFormat('file.:locale.:key')
            ->replace('key', ['foo','bar','baz'])
            ->replace('locale', ['nl','en']);

        $this->assertEquals([
            'file.nl.foo',
            'file.en.foo',
            'file.nl.bar',
            'file.en.bar',
            'file.nl.baz',
            'file.en.baz',
        ], $instance->get());
    }

    /** @test */
    public function if_placeholder_replacements_are_empty_it_leaves_the_key_intact()
    {
        $instance = ValidationNames::fromFormat('title')
            ->replace('locale', []);

        $this->assertEquals([
            'title',
        ], $instance->get());
    }

    /** @test */
    public function it_ignores_empty_translations_that_dont_belong_to_the_default_locale()
    {
        $instance = ValidationNames::fromFormat('trans.:locale.:key')
            ->replace('key', ['foo','bar'])
            ->replace('locale', ['nl','en', 'fr'])
            ->payload([
                'trans' => [
                    'nl' => ['foo' => '', 'bar' => null],
                    'fr' => ['foo' => 'value', 'bar' => null],
                    'en' => ['foo' => 'value', 'bar' => 'value'],
                ],
            ])
            ->requiredLocale('en');

        $this->assertEquals([
            'trans.en.foo',
            'trans.fr.foo',
            'trans.en.bar',
            'trans.fr.bar',
        ], $instance->get());
    }

    /** @test */
    public function it_removes_any_keys_that_are_marked_for_removal()
    {
        $instance = ValidationNames::fromFormat('files.:key')
            ->replace('key', ['foo','detach'])
            ->removeKeysContaining(['.detach']);

        $this->assertEquals([
            'files.foo',
        ], $instance->get());
    }

    /** @test */
    public function it_can_use_a_wildcard_for_removal()
    {
        $instance = ValidationNames::fromFormat(':key')
            ->replace('key', ['foo','bar','bor'])
            ->removeKeysContaining(['b*r']);

        $this->assertEquals([
            'foo',
        ], $instance->get());
    }

    /** @test */
    public function it_can_expand_the_names_for_given_locales()
    {
        $instance = ValidationNames::fromFormat('trans.:locale.:key')
            ->replace('key', ['foo','bar'])
            ->replace('locale', ['en','fr'])
            ->requiredLocale('nl');

        $this->assertEquals([
            'trans.en.foo',
            'trans.fr.foo',
            'trans.en.bar',
            'trans.fr.bar',
        ], $instance->get());
    }
}
