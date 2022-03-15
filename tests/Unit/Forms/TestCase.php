<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Forms;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Thinktomorrow\Chief\Forms\FormsServiceProvider;
use Thinktomorrow\Chief\Tests\Unit\UnitTestHelpers;
use Thinktomorrow\Chief\App\Providers\ViewServiceProvider;

class TestCase extends OrchestraTestCase
{
    use UnitTestHelpers;

    protected function getPackageProviders($app)
    {
        return [
            ViewServiceProvider::class,
            FormsServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Set nl as default locale for testing env
        config()->set('app.fallback_locale', 'nl');
    }
}
