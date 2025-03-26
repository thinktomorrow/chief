<?php

namespace Thinktomorrow\Chief\Menu\App\Actions;

use Thinktomorrow\Chief\Menu\Menu;

class SaveMenus
{
    private MenuApplication $menuApplication;

    public function __construct(MenuApplication $menuApplication)
    {
        $this->menuApplication = $menuApplication;
    }

    public function handle(string $type, array $menuValues): void
    {
        foreach ($menuValues as $menuId => $values) {

            // TODO: restrict this to prevent deletion of default menus
            if (is_null($values)) {
                $this->menuApplication->safeDelete(new DeleteMenu($menuId));

                continue;
            }

            if (str_starts_with($menuId, 'new-')) {
                $this->menuApplication->create(new CreateMenu($type, $values['locales'], $values['title']));

                continue;
            }

            $menu = Menu::findOrFail($menuId);

            $menu->title = $values['title'];
            $menu->setSiteLocales($values['locales']);

            $menu->save();
        }
    }
}
