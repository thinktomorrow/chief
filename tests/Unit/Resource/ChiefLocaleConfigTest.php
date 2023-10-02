<?php

namespace Thinktomorrow\Chief\Tests\Unit\Resource;

use Thinktomorrow\Chief\Resource\Locale\ChiefLocaleConfig;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class ChiefLocaleConfigTest extends ChiefTestCase
{
    public function test_it_gives_all_available_locales()
    {
        config()->set('chief.locales.admin', ['nl', 'fr']);

        $this->assertEquals(['nl', 'fr'], ChiefLocaleConfig::getLocales());
    }

    public function test_it_gives_active_locales_by_default_same_as_all_available()
    {
        config()->set('chief.locales.admin', ['nl', 'fr']);
        config()->set('chief.locales.site', null);

        $this->assertEquals(['nl', 'fr'], ChiefLocaleConfig::getSiteLocales());
    }

    public function test_it_gives_custom_active_locales()
    {
        config()->set('chief.locales.admin', ['nl', 'fr']);
        config()->set('chief.locales.site', ['fr', 'en']);

        $this->assertEquals(['fr', 'en'], ChiefLocaleConfig::getSiteLocales());
    }

    public function test_it_can_give_empty_array()
    {
        config()->set('chief.locales.admin', []);

        $this->assertEquals([], ChiefLocaleConfig::getLocales());
        $this->assertEquals([], ChiefLocaleConfig::getSiteLocales());
    }
}
