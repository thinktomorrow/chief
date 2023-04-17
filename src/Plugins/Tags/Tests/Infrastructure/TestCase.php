<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure;

use Thinktomorrow\Chief\Plugins\Tags\TagsServiceProvider;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

abstract class TestCase extends ChiefTestCase
{
    use TagTestHelpers;

    protected function getPackageProviders($app)
    {
        return [
            ...parent::getPackageProviders($app),
            TagsServiceProvider::class,
        ];
    }
}
