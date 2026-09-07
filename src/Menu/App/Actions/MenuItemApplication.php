<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\App\Actions;

use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Layouts\PageLayout;
use Thinktomorrow\Chief\Menu\Events\MenuItemCreated;
use Thinktomorrow\Chief\Menu\Events\MenuItemDeleted;
use Thinktomorrow\Chief\Menu\Events\MenuItemUpdated;
use Thinktomorrow\Chief\Menu\Exceptions\OwnerReferenceIsRequiredForInternalLinkType;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Menu\MenuLinkType;
use Thinktomorrow\Chief\Menu\Resources\MenuItemResource;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class MenuItemApplication
{
    public function __construct(
        private SanitizeUrl $sanitizeUrl,
        private MenuItemResource $resource,
        private FieldValidator $fieldValidator,
    ) {}

    public function create(CreateMenuItem $command): string
    {
        if ($command->getLinkType() == MenuLinkType::internal && ! $command->getOwnerReference()) {
            throw new OwnerReferenceIsRequiredForInternalLinkType('An owner reference is required for internal link types.');
        }

        $model = new MenuItem([
            'menu_id' => $command->getMenuId(),
            'type' => $command->getLinkType()->value,
            'parent_id' => $command->getParentId(),
            'order' => $this->getNextOrder($command->getMenuId(), $command->getParentId()),
        ]);

        $model->setRelation('menu', Menu::findOrFail($command->getMenuId()));

        $this->applyOwner($model, $command->getLinkType(), $command->getOwnerReference());
        $this->applyData($model, $command->getData(), $command->getLinkType());

        $fields = $this->fieldsForCreate($model);
        $this->fieldValidator->handle($fields, $command->getInput());
        app($this->resource->getSaveFieldsClass())->save($model, $fields, $command->getInput(), $command->getFiles());

        event(new MenuItemCreated((string) $model->id));

        return (string) $model->id;
    }

    public function update(UpdateMenuItem $command): void
    {
        if ($command->getLinkType() == MenuLinkType::internal && ! $command->getOwnerReference()) {
            throw new OwnerReferenceIsRequiredForInternalLinkType('An owner reference is required for internal link types.');
        }

        $model = MenuItem::with('menu')->findorFail($command->getMenuItemId());
        $parentChanged = $model->parent_id != $command->getParentId();

        $model->type = $command->getLinkType()->value;
        $model->parent_id = $command->getParentId();

        if ($parentChanged) {
            $model->order = $this->getNextOrder($model->menu_id, $command->getParentId(), (int) $model->id);
        }

        $this->applyOwner($model, $command->getLinkType(), $command->getOwnerReference());
        $this->applyData($model, $command->getData(), $command->getLinkType());

        $fields = $this->fieldsForUpdate($model);
        $this->fieldValidator->handle($fields, $command->getInput());
        app($this->resource->getSaveFieldsClass())->save($model, $fields, $command->getInput(), $command->getFiles());

        event(new MenuItemUpdated((string) $model->id));
    }

    /**
     * Only an internal link type keeps a reference to an owner page. Switching away from it
     * clears the reference along with the page data that was projected onto this item.
     */
    private function applyOwner(MenuItem $model, MenuLinkType $linkType, ?ModelReference $ownerReference): void
    {
        if ($linkType === MenuLinkType::internal) {
            $model->owner_type = $ownerReference->shortClassName();
            $model->owner_id = $ownerReference->id();

            return;
        }

        $model->owner_type = null;
        $model->owner_id = null;

        $this->removeDynamicIfPresent($model, 'owner_label');
    }

    /**
     * Only the custom link type owns its url: an internal type has it projected from its
     * owner page and a nolink type has none at all. Any other type therefore drops both
     * the submitted url and a url that is still stored from a previous link type.
     *
     * @param  array<string, array<string, mixed>>  $data
     */
    private function applyData(MenuItem $model, array $data, MenuLinkType $linkType): void
    {
        if ($linkType !== MenuLinkType::custom) {
            unset($data['url']);

            $this->removeDynamicIfPresent($model, 'url');
        }

        foreach ($data as $key => $values) {
            foreach ($values as $locale => $value) {
                if ($key == 'url' && $value) {
                    $value = $this->sanitizeUrl->sanitize($value);
                }

                $model->setDynamic($key, $value, $locale);
            }
        }
    }

    /**
     * Removing an absent key would still materialize an empty dynamic document
     * onto the model, so only touch keys that are actually stored.
     */
    private function removeDynamicIfPresent(MenuItem $model, string $key): void
    {
        if (array_key_exists($key, $model->rawDynamicValues())) {
            $model->removeDynamic($key);
        }
    }

    private function getNextOrder(string|int $menuId, ?string $parentId, ?int $ignoreMenuItemId = null): int
    {
        $highestOrder = MenuItem::query()
            ->where('menu_id', $menuId)
            ->where('parent_id', $parentId)
            ->when($ignoreMenuItemId, fn ($query) => $query->where('id', '<>', $ignoreMenuItemId))
            ->max('order');

        return is_null($highestOrder) ? 0 : $highestOrder + 1;
    }

    public function delete(DeleteMenuItem $command): void
    {
        $model = MenuItem::findorFail($command->getMenuItemId());

        $model->delete();

        event(new MenuItemDeleted((string) $model->id));
    }

    private function fieldsForCreate(MenuItem $menuItem): Fields
    {
        return PageLayout::make($this->resource->fields($menuItem))
            ->model($menuItem)
            ->getFields()
            ->filterByNotTagged(['edit', 'not-on-model-create', 'not-on-create']);
    }

    private function fieldsForUpdate(MenuItem $menuItem): Fields
    {
        return PageLayout::make($this->resource->fields($menuItem))
            ->model($menuItem)
            ->getFields();
    }
}
