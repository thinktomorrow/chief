<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\App\Actions;

use Thinktomorrow\Chief\App\Http\Requests\MenuRequest;
use Thinktomorrow\Chief\Menu\MenuLinkType;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class UpdateMenuItem
{
    private string $menuItemId;

    private string $linkType;

    private ?string $ownerReference;

    private ?string $parentId;

    private array $data;

    public function __construct(string $menuItemId, string $linkType, ?string $ownerReference, ?string $parentId, array $data)
    {
        $this->menuItemId = $menuItemId;
        $this->linkType = $linkType;
        $this->ownerReference = $ownerReference;
        $this->parentId = $parentId;
        $this->data = $data;
    }

    public static function fromRequest(string $menuItemId, MenuRequest $request): self
    {
        return new static(
            $menuItemId,
            $request->input('type'),
            $request->input('owner_reference'),
            ($request->input('allow_parent') && $request->input('parent_id')) ? $request->input('parent_id') : null,
            $request->input('trans', [])
        );
    }

    public function getMenuItemId(): string
    {
        return $this->menuItemId;
    }

    public function getLinkType(): MenuLinkType
    {
        return MenuLinkType::from($this->linkType);
    }

    public function getOwnerReference(): ?ModelReference
    {
        return $this->ownerReference ? ModelReference::fromString($this->ownerReference) : null;
    }

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
