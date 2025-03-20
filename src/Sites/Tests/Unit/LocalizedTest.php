<?php

namespace Thinktomorrow\Chief\Sites\Tests\Unit;

use Thinktomorrow\Chief\Sites\Tests\Fixtures\LocalizedFixture;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class LocalizedTest extends ChiefTestCase
{
    public function test_it_has_global_locale_by_default(): void
    {
        $model = new LocalizedFixture;

        app()->setLocale('en');

        $this->assertEquals('en', $model->getActiveLocale());
        $this->assertEquals([], $model->getFallbackLocales());
    }

    public function test_it_does_not_have_available_locales_by_default(): void
    {
        $model = new LocalizedFixture;

        $this->assertEquals([], $model->getLocales());
    }

    public function test_it_does_not_have_fallback_locales_by_default(): void
    {
        $model = new LocalizedFixture;

        $this->assertEquals([], $model->getFallbackLocales());
    }

    public function test_it_can_set_available_locales(): void
    {
        $model = new LocalizedFixture;

        $model->setLocales(['en', 'nl']);

        $this->assertEquals(['en', 'nl'], $model->getLocales());
    }

    public function test_it_can_set_active_locale()
    {
        $model = new LocalizedFixture;

        $model->setActiveLocale('en');

        $this->assertEquals('en', $model->getActiveLocale());
    }

    public function test_it_can_set_fall_locales(): void
    {
        $model = new LocalizedFixture;

        $model->setFallbackLocales(['en' => 'nl']);

        $this->assertEquals(['en' => 'nl'], $model->getFallbackLocales());
    }

    public function test_it_can_get_active_fallback_locale(): void
    {
        $model = new LocalizedFixture;

        $model->setFallbackLocales(['en' => 'nl']);

        $this->assertEquals('nl', $model->checkFallBackLocaleFor('en'));
    }

    public function test_a_fallback_locale_is_not_always_expected(): void
    {
        $model = new LocalizedFixture;

        $model->setFallbackLocales(['en' => 'nl']);

        $this->assertNull($model->checkFallBackLocaleFor('nl'));
    }
}
