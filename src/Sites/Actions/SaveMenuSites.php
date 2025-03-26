<?php

namespace Thinktomorrow\Chief\Sites\Actions;

use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuType;

class SaveMenuSites
{
    public function handle(MenuType $type, array $sites): void
    {
        $defaultMenu = Menu::findDefault($type->getType());

        // Per site (locale) we have a certain menu id. If null, the default menu is used.
        foreach ($sites as $site => $values) {
            $menuId = $values['menu'];

            // If the menu id is null, we use the default menu for this site.
            if (is_null($menuId)) {

                $defaultMenu->addActiveSite($site);
                $defaultMenu->save();

                // Cleanup all other active sites for this menu
                $this->removeActiveSite($site, [$defaultMenu->id]);

                continue;
            }

            $menu = Menu::findOrFail($menuId);
            $menu->addActiveSite($site);
            $menu->save();

            // Cleanup all other active sites for this menu
            $this->removeActiveSite($site, [$menu->id]);
        }
    }

    private function removeActiveSite(string $site, array $whitelistedMenuIds = []): void
    {
        $menus = Menu::byActiveSite($site)->whereNotIn('id', $whitelistedMenuIds)->get();

        foreach ($menus as $menu) {
            $menu->removeActiveSite($site);
            $menu->save();
        }
    }
}
