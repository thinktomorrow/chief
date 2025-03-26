<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\App\Actions;

use Thinktomorrow\Chief\Menu\Events\MenuCreated;
use Thinktomorrow\Chief\Menu\Events\MenuDeleted;
use Thinktomorrow\Chief\Menu\Events\MenuUpdated;
use Thinktomorrow\Chief\Menu\Exceptions\SafeMenuDeletionProtection;
use Thinktomorrow\Chief\Menu\Menu;

class MenuApplication
{
    public function create(CreateMenu $command): string
    {
        $model = Menu::create([
            'type' => $command->getType(),
            'sites' => $command->getSites(),
            'title' => $command->getTitle(),
        ]);

        event(new MenuCreated((string) $model->id));

        return (string) $model->id;
    }

    public function update(UpdateMenu $command): void
    {
        $model = Menu::findorFail($command->getMenuId());

        $model->update([
            'sites' => $command->getSites(),
            'title' => $command->getTitle(),
        ]);

        event(new MenuUpdated((string) $model->id));
    }

    public function delete(DeleteMenu $command): void
    {
        $model = Menu::findorFail($command->getMenuId());

        $model->delete();

        event(new MenuDeleted((string) $model->id));
    }

    /**
     * Delete the menu but only if there is still a menu of this type left.
     */
    public function safeDelete(DeleteMenu $command): void
    {
        $model = Menu::findorFail($command->getMenuId());

        if (Menu::where('type', $model->type)->count() <= 1) {
            throw new SafeMenuDeletionProtection('Cannot delete the last menu of this type ['.$model->type.'].');
        }

        $model->delete();

        event(new MenuDeleted((string) $model->id));
    }
}
