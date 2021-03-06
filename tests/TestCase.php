<?php

namespace Thinktomorrow\Chief\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Thinktomorrow\Chief\App\Providers\ChiefServiceProvider;
use Thinktomorrow\Chief\Shared\Helpers\Memoize;
use Thinktomorrow\Chief\Site\Urls\MemoizedUrlRecord;
use Thinktomorrow\Chief\Tests\Shared\ManagedModelFactory;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;
use Thinktomorrow\Chief\Tests\Shared\TestHelpers;
use Thinktomorrow\Chief\Tests\Shared\TestingWithFiles;
use Thinktomorrow\Chief\Tests\Shared\TestingWithManagers;

abstract class TestCase extends OrchestraTestCase
{
    use TestHelpers;
    use TestingWithManagers;
    use TestingWithFiles;

    protected $protectTestEnvironment = true;

    protected function getPackageProviders($app)
    {
        return [
            ChiefServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerResponseMacros();

        // Set nl as default locale for testing env
        config()->set('app.fallback_locale', 'nl');
    }

    protected function getEnvironmentSetUp($app)
    {
        //
    }

    protected function tearDown(): void
    {
        ManagerFactory::clearTemporaryFiles();
        ManagedModelFactory::clearTemporaryFiles();

        // Clear out any memoized values
        Memoize::clear();
        MemoizedUrlRecord::clearCachedRecords();

        parent::tearDown();
    }
}
