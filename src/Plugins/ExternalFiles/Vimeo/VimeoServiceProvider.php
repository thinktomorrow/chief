<?php

namespace Thinktomorrow\Chief\Plugins\ExternalFiles\Vimeo;

use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;
use Vimeo\Vimeo;

class VimeoServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {

    }

    public function register()
    {
        parent::register();

        $this->app->bind(Vimeo::class, function(){
            return new Vimeo(
                'f93352aab45a194016dea1f3271076143443dfad',
                'z9PedDwbBVXsLXR7tCnq2seEvnxMtibRVIy69Agy6BRlOJc5Ze6WvtHftpS5XWHwj7UVgJRzINzXgEE3SoIInnchACWADoacAIVaH3pEehssX+jmG7SrY20prdkKbHiV',
                'c2e9ad16183cf74c33a0f5ca12622dba',
            );
        });
        //
    }

    //
}
