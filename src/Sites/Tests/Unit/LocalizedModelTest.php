<?php

namespace Thinktomorrow\Chief\Sites\Tests\Unit;

use Thinktomorrow\Chief\Sites\Tests\Fixtures\LocalizedFixture;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class LocalizedModelTest extends ChiefTestCase
{
    public function test_it_can_get_localized_value()
    {
        $model = new LocalizedFixture;

        $model->setLocales(['nl', 'en']);

        $model->setDynamic('title', 'Nederlandse titel', 'nl');
        $model->setDynamic('title', 'English title', 'en');

        $model->setActiveLocale('en');
        $this->assertEquals('English title', $model->title);

        $model->setActiveLocale('nl');
        $this->assertEquals('Nederlandse titel', $model->title);
    }

    public function test_it_can_get_fallback_value(): void
    {
        $model = new LocalizedFixture;

        $model->setLocales(['nl', 'en']);
        $model->setFallbackLocales(['en' => 'nl']);

        $model->setDynamic('title', 'Nederlandse titel', 'nl');

        $model->setActiveLocale('en');
        $this->assertEquals('Nederlandse titel', $model->title);
    }

    public function test_when_it_does_not_have_fallback_value(): void
    {
        $model = new LocalizedFixture;

        $model->setLocales(['nl', 'en']);

        $model->setDynamic('title', 'Nederlandse titel', 'nl');

        $model->setActiveLocale('en');
        $this->assertEquals(null, $model->title);
    }
}
