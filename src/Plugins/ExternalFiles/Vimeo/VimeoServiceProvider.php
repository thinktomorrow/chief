<?php

namespace Thinktomorrow\Chief\Plugins\ExternalFiles\Vimeo;

use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;
use Thinktomorrow\Chief\Assets\App\ExternalFiles\DriverFactory;

class VimeoServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        // Register Vimeo asset driver - so the file upload components know which external links can be used.
        DriverFactory::addDriver('vimeo', VimeoDriver::class);
    }

    public function register()
    {
        parent::register();
    }

    //
}
