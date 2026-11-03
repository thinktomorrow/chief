<?php

namespace Thinktomorrow\Chief\Plugins\ExternalFiles;

use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;
use Thinktomorrow\Chief\Plugins\Docs\DocSources;

/**
 * Base for the external file driver providers. The ExternalFiles guides are
 * shared by all drivers, so they are added once however many drivers load.
 */
abstract class ExternalFileDriverServiceProvider extends ChiefPluginServiceProvider
{
    public function register()
    {
        parent::register();

        $this->callAfterResolving(DocSources::class, function (DocSources $sources) {
            if (! $sources->has('external-files')) {
                $sources->add('external-files', __DIR__.'/resources/docs');
            }
        });
    }
}
