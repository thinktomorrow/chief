<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Locales;

use PHPUnit\Framework\TestCase;
use Thinktomorrow\Chief\Forms\Fields\Locales\LocalizedFormKey;

class LocalizedFormKeyTest extends TestCase
{
    public function test_it_returns_the_default_localized_format()
    {
        $this->assertEquals('trans.nl.xxx', LocalizedFormKey::make()->get('xxx', 'nl'));
    }

    public function test_it_returns_the_matrix_of_different_locales()
    {
        $this->assertEquals(
            ['trans.nl.xxx', 'trans.en.xxx'],
            LocalizedFormKey::make()->matrix('xxx', ['nl', 'en'])
        );
    }

    public function test_it_returns_the_key_with_brackets()
    {
        $this->assertEquals(
            'trans[nl][xxx]',
            LocalizedFormKey::make()
                ->bracketed()
                ->get('xxx', 'nl')
        );
    }

    public function test_it_can_use_a_custom_template()
    {
        $this->assertEquals('custom_nl_xxx', LocalizedFormKey::make()
            ->template('custom_:locale_:name')
            ->get('xxx', 'nl'));
    }

    public function test_unused_placeholders_are_removed()
    {
        $this->assertEquals('custom.xxx', LocalizedFormKey::make()
            ->template('custom.:locale.:name')
            ->get('xxx'));

        $this->assertEquals('custom.xxx', LocalizedFormKey::make()
            ->template('custom.:name.:random')
            ->get('xxx', 'nl'));
    }

    public function test_unused_placeholders_can_be_preserved()
    {
        $this->assertEquals('custom.:locale.xxx', LocalizedFormKey::make()
            ->template('custom.:locale.:name')
            ->get('xxx', null, false));
    }

    public function test_it_can_replace_a_placeholder_value_in_the_key()
    {
        $this->assertEquals(
            'trans.nl.foobar',
            LocalizedFormKey::make()
                ->replace('key', 'foobar')
                ->get(':key', 'nl')
        );
    }

    public function test_it_can_replace_a_placeholder_value_that_contains_brackets()
    {
        $formKey = LocalizedFormKey::make()
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
            'trans.nl.foobar',
            'trans.fr.foobar',
            ],
            LocalizedFormKey::make()->matrix('foobar', ['nl','fr'])
        );
    }
}
