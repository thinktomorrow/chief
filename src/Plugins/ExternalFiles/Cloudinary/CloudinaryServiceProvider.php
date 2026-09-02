<?php

namespace Thinktomorrow\Chief\Plugins\ExternalFiles\Cloudinary;

use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

class CloudinaryServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        //
    }

    public function register()
    {
        parent::register();

        //        cloudinary.config({
        //  cloud_name: 'think-tomorrow',
        //  api_key: '941447417326192',
        //  api_secret: '3kKkz8cHfy1Rpv3WIGnJaF96g0Y'
        // });
        //
    }
}
