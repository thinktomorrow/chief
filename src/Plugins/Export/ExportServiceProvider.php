<?php

namespace Thinktomorrow\Chief\Plugins\Export;

use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;
use Thinktomorrow\Chief\Plugins\Export\Export\ExportAllCommand;
use Thinktomorrow\Chief\Plugins\Export\Export\ExportMenuCommand;
use Thinktomorrow\Chief\Plugins\Export\Export\ExportResourceCommand;
use Thinktomorrow\Chief\Plugins\Export\Export\ExportTextCommand;
use Thinktomorrow\Chief\Plugins\Export\Import\ImportResourceCommand;

class ExportServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->commands(['command.chief:export-resource']);
        $this->commands(['command.chief:export-menu']);
        $this->commands(['command.chief:export-text']);
        $this->commands(['command.chief:export-all']);
        $this->commands(['command.chief:trans-import']);

        $this->app->bind('command.chief:export-resource', ExportResourceCommand::class);
        $this->app->bind('command.chief:export-menu', ExportMenuCommand::class);
        $this->app->bind('command.chief:export-text', ExportTextCommand::class);
        $this->app->bind('command.chief:export-all', ExportAllCommand::class);
        $this->app->bind('command.chief:trans-import', ImportResourceCommand::class);
    }
}
