<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Infrastructure;

use Thinktomorrow\Chief\Tests\ChiefTestCase;

abstract class TestCase extends ChiefTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ...parent::getPackageProviders($app),
            TranslationsExportServiceProvider::class, // method is called once on the first run of the testsuite so this is not called. Therefore we set the service provider in the general testsuite...
        ];
    }
}
