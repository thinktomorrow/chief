<?php

namespace Thinktomorrow\Chief\Plugins\HotSpots\Tests;

use Thinktomorrow\Chief\Plugins\HotSpots\HotSpotsServiceProvider;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

abstract class TestCase extends ChiefTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ...parent::getPackageProviders($app),
            HotSpotsServiceProvider::class,
        ];
    }
}
