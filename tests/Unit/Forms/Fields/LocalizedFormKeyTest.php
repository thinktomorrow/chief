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
    public function itReturnsTheDefaultLocalizedFormat()
    {
        $this->assertEquals('trans.nl.xxx', LocalizedFormKey::make()->get('xxx', 'nl'));
    }

    /** @test */
    public function itReturnsTheMatrixOfDifferentLocales()
    {
        $this->assertEquals(
            ['trans.nl.xxx', 'trans.en.xxx'],
            LocalizedFormKey::make()->matrix('xxx', ['nl', 'en'])
        );
    }

    /** @test */
    public function itReturnsTheKeyWithBrackets()
    {
        $this->assertEquals(
            'trans[nl][xxx]',
            LocalizedFormKey::make()
                ->bracketed()
                ->get('xxx', 'nl')
        );
    }

    /** @test */
    public function itCanUseACustomTemplate()
    {
        $this->assertEquals('custom_nl_xxx', LocalizedFormKey::make()
            ->template('custom_:locale_:name')
            ->get('xxx', 'nl'));
    }

    /** @test */
    public function itCanReplaceAPlaceholderValueInTheKey()
    {
        $this->assertEquals(
            'trans.nl.foobar',
            LocalizedFormKey::make()
                ->replace('key', 'foobar')
                ->get(':key', 'nl')
        );
    }

    /** @test */
    public function itCanCrossJoinMultipleLocaleKeys()
    {
        $this->assertEquals([
            'trans.nl.foobar',
            'trans.fr.foobar',
            ], LocalizedFormKey::make()->matrix('foobar',['nl','fr'])
        );
    }
}
