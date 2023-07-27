<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests;

use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Thinktomorrow\Chief\App\Providers\ViewServiceProvider;
use Thinktomorrow\Chief\Assets\AssetsServiceProvider;
use Thinktomorrow\Chief\Forms\FormsServiceProvider;
use Thinktomorrow\Chief\Tests\Unit\UnitTestHelpers;

class TestCase extends OrchestraTestCase
{
    use UnitTestHelpers;

    public function setUp(): void
    {
        parent::setUp();

        // Set nl as default locale for testing env
        config()->set('app.fallback_locale', 'nl');
    }

    protected function getPackageProviders($app)
    {
        return [
            ViewServiceProvider::class,
            FormsServiceProvider::class,
            LivewireServiceProvider::class,
            AssetsServiceProvider::class,
        ];
    }
}
