<?php

namespace Thinktomorrow\Chief\Plugins\ExternalFiles\Youtube;

use Thinktomorrow\Chief\Assets\App\ExternalFiles\DriverFactory;
use Thinktomorrow\Chief\Plugins\ExternalFiles\ExternalFileDriverServiceProvider;

class YoutubeServiceProvider extends ExternalFileDriverServiceProvider
{
    public function boot(): void
    {
        // Register Youtube asset driver - so the file upload components know which external links can be used.
        DriverFactory::addDriver('youtube', YoutubeDriver::class);
    }

    public function register()
    {
        parent::register();
    }
}
