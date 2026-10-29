<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Plugins\Docs\DocSources;

/**
 * A project plugin provider that does not extend Chief's plugin base class.
 */
class AcmeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->make(DocSources::class)->add('acme', __DIR__.'/fixtures/acme');
    }
}
