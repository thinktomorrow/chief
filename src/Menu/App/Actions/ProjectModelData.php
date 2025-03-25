<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\App\Actions;

use Thinktomorrow\Chief\Forms\Events\FormUpdated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelArchived;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelDeleted;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelPublished;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUnPublished;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUrlUpdated;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Menu\Events\MenuItemCreated;
use Thinktomorrow\Chief\Menu\Events\MenuItemUpdated;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Menu\MenuItemStatus;
use Thinktomorrow\Chief\Menu\MenuLinkType;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Url\Url;

class ProjectModelData
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function onMenuItemCreated(MenuItemCreated $event): void
    {
        $this->handleByMenuItem(MenuItem::find($event->menuItemId));
    }

    public function onMenuItemUpdated(MenuItemUpdated $event): void
    {
        $this->handleByMenuItem(MenuItem::find($event->menuItemId));
    }

    public function onFormUpdated(FormUpdated $event): void
    {
        $this->handleByOwner($event->modelReference->shortClassName(), $event->modelReference->id());
    }

    public function onManagedModelUrlUpdated(ManagedModelUrlUpdated $event): void
    {
        $this->handleByOwner($event->modelReference->shortClassName(), $event->modelReference->id());
    }

    public function onManagedModelUpdated(ManagedModelUpdated $event): void
    {
        $this->handleByOwner($event->modelReference->shortClassName(), $event->modelReference->id());
    }

    public function onManagedModelArchived(ManagedModelArchived $event): void
    {
        $this->handleByOwner($event->modelReference->shortClassName(), $event->modelReference->id());
    }

    public function onManagedModelPublished(ManagedModelPublished $event): void
    {
        $this->handleByOwner($event->modelReference->shortClassName(), $event->modelReference->id());
    }

    public function onManagedModelUnPublished(ManagedModelUnPublished $event): void
    {
        $this->handleByOwner($event->modelReference->shortClassName(), $event->modelReference->id());
    }

    public function onManagedModelDeleted(ManagedModelDeleted $event): void
    {
        $this->removeOwner($event->modelReference->shortClassName(), $event->modelReference->id());
    }

    public function handleByOwner(string $ownerType, $ownerId): void
    {
        // Find any related menu items
        $menuItems = MenuItem::getByOwner($ownerType, $ownerId);

        if ($menuItems->count() < 1) {
            return;
        }

        foreach ($menuItems as $menuItem) {
            $this->handleByMenuItem($menuItem);
        }
    }

    public function handleByMenuItem(MenuItem $menuItem): void
    {
        if (! $menuItem->ofType(MenuLinkType::internal->value)) {
            return;
        }

        $model = $menuItem->owner;

        if (! $model) {
            return;
        }

        if ($model instanceof StatefulContract) {
            $menuItem->setStatus(! $model->inOnlineState() ? MenuItemStatus::offline : MenuItemStatus::online);
        }

        /** @var PageResource $resource */
        $resource = $this->registry->findResourceByModel($model::class);

        $originalLocale = app()->getLocale();
        $locales = \Thinktomorrow\Chief\Sites\Locales\ChiefLocales::locales();

        foreach ($locales as $locale) {
            app()->setLocale($locale); // only way to get localized pagetitle
            $menuItem->setOwnerLabel($resource->getPageTitle($model), $locale);

            if ($model instanceof Visitable) {
                $fullUrl = $model->url($locale);
                $path = Url::fromString($fullUrl)->getPath();

                /**
                 * For root urls the path is null but in that case we want to have a '/' as path
                 * so we can safely prepend this on each path. However for real missing links
                 * we still set null as url on the menu item.
                 */
                $menuItem->setUrl($fullUrl ? '/'.$path : null, $locale);
            }
        }

        // Reset back to original locale
        app()->setLocale($originalLocale);

        $menuItem->save();
    }

    private function removeOwner(string $ownerType, $ownerId): void
    {
        $menuItems = MenuItem::getByOwner($ownerType, $ownerId);

        foreach ($menuItems as $menuItem) {
            $menuItem->type = MenuLinkType::nolink->value;
            $menuItem->owner_type = null;
            $menuItem->owner_id = null;
            $menuItem->save();
        }
    }
}
