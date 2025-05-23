<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\App\Actions;

use Thinktomorrow\Chief\Menu\Events\MenuCreated;
use Thinktomorrow\Chief\Menu\Events\MenuDeleted;
use Thinktomorrow\Chief\Menu\Events\MenuUpdated;
use Thinktomorrow\Chief\Menu\Exceptions\SafeMenuDeletionProtection;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Sites\Actions\SyncActiveSites;

class MenuApplication
{
    private SyncActiveSites $syncActiveSites;

    public function __construct(SyncActiveSites $syncActiveSites)
    {
        $this->syncActiveSites = $syncActiveSites;
    }

    public function create(CreateMenu $command): string
    {
        $model = Menu::create([
            'type' => $command->getType(),
            'allowed_sites' => $command->getAllowedSites(),
            'active_sites' => $command->getActiveSites(),
            'title' => $command->getTitle(),
        ]);

        $this->syncActiveSites->handle(
            $model,
            Menu::where('type', $command->getType())->get()->reject(fn ($c) => $c->id == $model->id)
        );

        event(new MenuCreated((string) $model->id));

        return (string) $model->id;
    }

    public function update(UpdateMenu $command): void
    {
        $model = Menu::findorFail($command->getMenuId());

        $model->update([
            'allowed_sites' => $command->getAllowedSites(),
            'active_sites' => $command->getActiveSites(),
            'title' => $command->getTitle(),
        ]);

        $this->syncActiveSites->handle(
            $model,
            Menu::where('type', $model->type)->get()->reject(fn ($c) => $c->id == $model->id)
        );

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
     * Also, a menu cannot be deleted if it is still in use by a site.
     */
    public function safeDelete(DeleteMenu $command): void
    {
        $model = Menu::findorFail($command->getMenuId());

        $this->preventUnsafeDeletion($model);

        $model->delete();

        event(new MenuDeleted((string) $model->id));
    }

    private function preventUnsafeDeletion(Menu $model): void
    {
        if (count($model->getActiveSites()) > 0) {
            throw new SafeMenuDeletionProtection('Cannot delete menu ['.$model->type.', id: '.$model->id.'] that is still in use by a site.');
        }

        if (Menu::where('type', $model->type)->count() <= 1) {
            throw new SafeMenuDeletionProtection('Cannot delete the last menu of this type ['.$model->type.'].');
        }
    }
}
