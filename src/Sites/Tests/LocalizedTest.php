<?php

namespace Thinktomorrow\Chief\Sites\Tests;

use Thinktomorrow\Chief\Sites\Tests\Fixtures\LocalizedFixture;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class LocalizedTest extends ChiefTestCase
{
    public function test_it_can_set_locales()
    {
        $model = new LocalizedFixture;

        $model->setLocale('en');

        $this->assertEquals('en', $model->getLocale());
    }
}
