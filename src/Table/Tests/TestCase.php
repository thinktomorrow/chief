<?php

namespace Thinktomorrow\Chief\Table\Tests;

use Thinktomorrow\Chief\Plugins\Tags\TagsServiceProvider;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

abstract class TestCase extends ChiefTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ...parent::getPackageProviders($app),
            TagsServiceProvider::class,
        ];
    }
}
