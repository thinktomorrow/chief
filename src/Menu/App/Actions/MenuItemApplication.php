<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\App\Actions;

use Thinktomorrow\Chief\Menu\Events\MenuItemCreated;
use Thinktomorrow\Chief\Menu\Events\MenuItemDeleted;
use Thinktomorrow\Chief\Menu\Events\MenuItemUpdated;
use Thinktomorrow\Chief\Menu\Exceptions\OwnerReferenceIsRequiredForInternalLinkType;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Menu\MenuLinkType;

class MenuItemApplication
{
    private SanitizeUrl $sanitizeUrl;

    public function __construct(SanitizeUrl $sanitizeUrl)
    {
        $this->sanitizeUrl = $sanitizeUrl;
    }

    public function create(CreateMenuItem $command): string
    {
        if ($command->getLinkType() == MenuLinkType::internal && ! $command->getOwnerReference()) {
            throw new OwnerReferenceIsRequiredForInternalLinkType('An owner reference is required for internal link types.');
        }

        $model = MenuItem::create([
            'menu_id' => $command->getMenuId(),
            'type' => $command->getLinkType(),
            'parent_id' => $command->getParentId(),
        ]);

        if ($command->getOwnerReference()) {
            $model->owner_type = $command->getOwnerReference()->shortClassName();
            $model->owner_id = $command->getOwnerReference()->id();
        }

        foreach ($command->getData() as $key => $values) {
            foreach ($values as $locale => $value) {
                if ($key == 'url' && $value) {
                    $value = $this->sanitizeUrl->sanitize($value);
                }
                $model->setDynamic($key, $value, $locale);
            }
        }

        $model->save();

        event(new MenuItemCreated((string) $model->id));

        return (string) $model->id;
    }

    public function update(UpdateMenuItem $command): void
    {
        if ($command->getLinkType() == MenuLinkType::internal && ! $command->getOwnerReference()) {
            throw new OwnerReferenceIsRequiredForInternalLinkType('An owner reference is required for internal link types.');
        }

        $model = MenuItem::findorFail($command->getMenuItemId());
        $model->type = $command->getLinkType();
        $model->parent_id = $command->getParentId();

        if ($command->getOwnerReference()) {
            $model->owner_type = $command->getOwnerReference()->shortClassName();
            $model->owner_id = $command->getOwnerReference()->id();
        }

        // Remove url if no link is given for custom link type
        if ($command->getLinkType() === MenuLinkType::nolink) {
            $model->removeDynamic('url');
        }

        foreach ($command->getData() as $key => $values) {
            foreach ($values as $locale => $value) {
                if ($key == 'url' && $value) {
                    $value = $this->sanitizeUrl->sanitize($value);
                }
                $model->setDynamic($key, $value, $locale);
            }
        }

        $model->save();

        event(new MenuItemUpdated((string) $model->id));
    }

    public function delete(DeleteMenuItem $command): void
    {
        $model = MenuItem::findorFail($command->getMenuItemId());

        $model->delete();

        event(new MenuItemDeleted((string) $model->id));
    }
}
