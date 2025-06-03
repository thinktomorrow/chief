<?php

namespace Thinktomorrow\Chief\Menu\App\Commands;

use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\Menu\App\Actions\CreateMenu;
use Thinktomorrow\Chief\Menu\App\Actions\MenuApplication;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuType;
use Thinktomorrow\Chief\Sites\ChiefSites;

class DefaultMenusCommand extends BaseCommand
{
    protected $signature = 'chief:default-menus';

    protected $description = 'Add default menus for each type.';

    private MenuApplication $menuApplication;

    public function __construct(MenuApplication $menuApplication)
    {
        parent::__construct();

        $this->menuApplication = $menuApplication;
    }

    public function handle(): void
    {
        $menuTypes = MenuType::all();

        foreach ($menuTypes as $menuType) {

            // If menu already exists, skip creation
            if (Menu::where('type', $menuType->getType())->exists()) {
                $this->warn("A menu of type '{$menuType->getType()}' already exists. Skipping creation.");

                continue;
            }

            $this->menuApplication->create(new CreateMenu($menuType->getType(), ChiefSites::locales(), ChiefSites::locales(), $menuType->getLabel()));
            $this->info("Menu '{$menuType->getType()}' was created.");
        }
    }
}
