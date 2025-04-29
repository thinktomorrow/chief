<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests;

use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Thinktomorrow\Chief\App\Providers\ViewServiceProvider;
use Thinktomorrow\Chief\Assets\AssetsServiceProvider;
use Thinktomorrow\Chief\Forms\FormsServiceProvider;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Tests\Unit\UnitTestHelpers;

class FormsTestCase extends OrchestraTestCase
{
    use UnitTestHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        // Set nl as default locale for testing env
        config()->set('app.fallback_locale', 'nl');

        config()->set('chief.sites', [
            ['locale' => 'en', 'fallback_locale' => null],
            ['locale' => 'nl', 'fallback_locale' => 'en', 'primary' => true], // First is primary
            ['locale' => 'fr', 'fallback_locale' => 'nl'],
        ]);
    }

    protected function tearDown(): void
    {
        ChiefSites::clearCache();

        parent::tearDown();
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
