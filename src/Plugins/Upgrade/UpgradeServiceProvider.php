<?php

namespace Thinktomorrow\Chief\Plugins\Upgrade;

use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;
use Thinktomorrow\Chief\Plugins\Upgrade\Commands\UpgradeFrom9To10Command;

class UpgradeServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->commands(['command.chief:upgrade-from-9-to-10']);

        $this->app->bind('command.chief:upgrade-from-9-to-10', UpgradeFrom9To10Command::class);
    }
}
