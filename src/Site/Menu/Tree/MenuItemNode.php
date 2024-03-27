<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu\Tree;

use Thinktomorrow\Chief\Site\Menu\MenuItemStatus;
use Thinktomorrow\Vine\DefaultNode;
use Thinktomorrow\Vine\Node;

class MenuItemNode extends DefaultNode implements Node
{
    private MenuItemStatus $status;
    private ?string $label;
    private ?string $url;
    private string $adminUrlLabel;

    public function __construct(MenuItemStatus $status, ?string $label, ?string $url, string $adminUrlLabel, string $id, ?string $parentId, int $order)
    {
        parent::__construct([
            'id' => $id,
            'parent_id' => $parentId,
            'order' => $order,
        ]);

        $this->status = $status;
        $this->label = $label;
        $this->url = $url;
        $this->adminUrlLabel = $adminUrlLabel;
    }

    public function getId()
    {
        return $this->getNodeId();
    }

    public function getParentId()
    {
        return $this->getParentNodeId();
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    public function getLabelOrFallback(): string
    {
        return $this->label ?: $this->getAdminUrlLabel();
    }

    public function getAdminUrlLabel(): string
    {
        return $this->adminUrlLabel;
    }

    public function isOffline(): bool
    {
        return $this->status != MenuItemStatus::online;
    }
}
