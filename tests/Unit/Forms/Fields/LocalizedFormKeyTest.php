<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields;

use PHPUnit\Framework\TestCase;
use Thinktomorrow\Chief\Forms\Fields\Locale\LocalizedFormKey;

/**
 * @internal
 * @coversNothing
 */
class LocalizedFormKeyTest extends TestCase
{
    /** @test */
    public function it_returns_the_default_localized_format()
    {
        $this->assertEquals('trans.nl.xxx', LocalizedFormKey::make()->get('xxx', 'nl'));
    }

    /** @test */
    public function it_returns_the_matrix_of_different_locales()
    {
        $this->assertEquals(
            ['trans.nl.xxx', 'trans.en.xxx'],
            LocalizedFormKey::make()->matrix('xxx', ['nl', 'en'])
        );
    }

    /** @test */
    public function it_returns_the_key_with_brackets()
    {
        $this->assertEquals(
            'trans[nl][xxx]',
            LocalizedFormKey::make()
                ->bracketed()
                ->get('xxx', 'nl')
        );
    }

    /** @test */
    public function it_can_use_a_custom_template()
    {
        $this->assertEquals('custom_nl_xxx', LocalizedFormKey::make()
            ->template('custom_:locale_:name')
            ->get('xxx', 'nl'));
    }

    /** @test */
    public function unused_placeholders_are_removed()
    {
        $this->assertEquals('custom.xxx', LocalizedFormKey::make()
            ->template('custom.:locale.:name')
            ->get('xxx'));

        $this->assertEquals('custom.xxx', LocalizedFormKey::make()
            ->template('custom.:name.:random')
            ->get('xxx', 'nl'));
    }

    /** @test */
    public function unused_placeholders_can_be_preserved()
    {
        $this->assertEquals('custom.:locale.xxx', LocalizedFormKey::make()
            ->template('custom.:locale.:name')
            ->get('xxx', null, false));
    }

    /** @test */
    public function it_can_replace_a_placeholder_value_in_the_key()
    {
        $this->assertEquals(
            'trans.nl.foobar',
            LocalizedFormKey::make()
                ->replace('key', 'foobar')
                ->get(':key', 'nl')
        );
    }

    /** @test */
    public function it_can_cross_join_multiple_locale_keys()
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
