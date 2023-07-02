<?php

namespace Thinktomorrow\Chief\Plugins\AdminToast\Tests;

use Thinktomorrow\Chief\Plugins\AdminToast\AdminToastServiceProvider;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

abstract class TestCase extends ChiefTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ...parent::getPackageProviders($app),
            AdminToastServiceProvider::class,
        ];
    }
}
