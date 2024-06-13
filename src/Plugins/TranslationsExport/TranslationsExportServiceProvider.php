<?php

namespace Thinktomorrow\Chief\Plugins\TranslationsExport;

use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;
use Thinktomorrow\Chief\Plugins\TranslationsExport\Export\TranslationsExportCommand;

class TranslationsExportServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->commands(['command.chief:trans-export']);
        $this->app->bind('command.chief:trans-export', TranslationsExportCommand::class);
    }
}
