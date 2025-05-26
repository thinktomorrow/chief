<?php

namespace Thinktomorrow\Chief\Menu\App\Commands;

use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\Menu\App\Actions\ProjectModelData;
use Thinktomorrow\Chief\Menu\MenuItem;

class ProjectMenuCommand extends BaseCommand
{
    protected $signature = 'chief:project-menu';

    protected $description = 'Update all menu items with the current model data.';

    private ProjectModelData $projectModelData;

    public function __construct(ProjectModelData $projectModelData)
    {
        parent::__construct();

        $this->projectModelData = $projectModelData;
    }

    public function handle(): void
    {
        $menuItems = MenuItem::all();

        $this->info(count($menuItems).' menuitems will be updated.');

        foreach ($menuItems as $menuItem) {
            $this->projectModelData->handleByMenuItem($menuItem);
        }

        $this->info(' All menuitems updated.');
    }
}
