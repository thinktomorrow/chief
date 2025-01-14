<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests;

use Maatwebsite\Excel\ExcelServiceProvider;
use Thinktomorrow\Chief\Plugins\Export\ExportServiceProvider;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

abstract class TestCase extends ChiefTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ...parent::getPackageProviders($app),
            ExcelServiceProvider::class,
            ExportServiceProvider::class, // method is called once on the first run of the testsuite so this is not called. Therefore we set the service provider in the general testsuite...
        ];
    }
}
