<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure;

use Thinktomorrow\Chief\Plugins\TimeTable\TimeTableServiceProvider;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

abstract class TestCase extends ChiefTestCase
{
    use TimeTableTestHelpers;

    protected function getPackageProviders($app)
    {
        return [
            ...parent::getPackageProviders($app),
            TimeTableServiceProvider::class,
        ];
    }
}
