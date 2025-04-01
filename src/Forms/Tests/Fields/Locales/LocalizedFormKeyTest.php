<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Locales;

use PHPUnit\Framework\TestCase;
use Thinktomorrow\Chief\Forms\Fields\FieldName\FieldName;

class LocalizedFormKeyTest extends TestCase
{
    public function test_it_returns_the_default_localized_format()
    {
        $this->assertEquals('xxx.nl', FieldName::make()->get('xxx', 'nl'));
    }

    public function test_it_returns_the_matrix_of_different_locales()
    {
        $this->assertEquals(
            ['nl' => 'xxx.nl', 'en' => 'xxx.en'],
            FieldName::make()->matrix('xxx', ['nl', 'en'])
        );
    }

    public function test_it_returns_the_key_with_brackets()
    {
        $this->assertEquals(
            'xxx[nl]',
            FieldName::make()
                ->bracketed()
                ->get('xxx', 'nl')
        );
    }

    public function test_it_can_use_a_custom_template()
    {
        $this->assertEquals('custom_nl_xxx', FieldName::make()
            ->template('custom_:locale_:name')
            ->get('xxx', 'nl'));
    }

    public function test_unused_placeholders_are_removed()
    {
        $this->assertEquals('custom.xxx', FieldName::make()
            ->template('custom.:locale.:name')
            ->get('xxx'));

        $this->assertEquals('custom.xxx', FieldName::make()
            ->template('custom.:name.:random')
            ->get('xxx', 'nl'));
    }

    public function test_unused_placeholders_can_be_preserved()
    {
        $this->assertEquals('custom.:locale.xxx', FieldName::make()
            ->template('custom.:locale.:name')
            ->get('xxx', null, false));
    }

    public function test_it_can_replace_a_placeholder_value_in_the_key()
    {
        $this->assertEquals(
            'foobar.nl',
            FieldName::make()
                ->replace('key', 'foobar')
                ->get(':key', 'nl')
        );
    }

    public function test_it_can_replace_a_placeholder_value_that_contains_brackets()
    {
        $formKey = FieldName::make()
            ->template(':prefix.:name.:locale')
            ->replace('prefix', 'repeat_values[2][repeat_values_nested]')
            ->bracketed()
            ->get('nested-value');

        $this->assertEquals('repeat_values[2][repeat_values_nested][nested-value]', $formKey);
    }

    public function test_it_can_cross_join_multiple_locale_keys()
    {
        $this->assertEquals(
            [
                'nl' => 'trans.nl.foobar',
                'fr' => 'trans.fr.foobar',
            ],
            FieldName::make()->template('trans.:locale.:name')->matrix('foobar', ['nl', 'fr'])
        );
    }
}
