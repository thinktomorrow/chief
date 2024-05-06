<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu\Tree;

use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Chief\Site\Menu\MenuItemStatus;
use Thinktomorrow\Vine\DefaultNode;
use Thinktomorrow\Vine\Node;

class MenuItemNode extends DefaultNode implements Node
{
    private MenuItemStatus $status;
    private string $label;
    private ?string $url;
    private string $adminUrlLabel;

    private function __construct(MenuItemStatus $status, string $label, ?string $url, string $adminUrlLabel, string $id, ?string $parentId, int $order)
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

    public static function fromModel(MenuItem $model, string $locale): Node
    {
        return new static(
            $model->getStatus(),
            $model->getLabel($locale) ?: $model->getAdminUrlLabel($locale),
            $model->getUrl($locale),
            $model->getAdminUrlLabel($locale),
            (string) $model->id,
            (string) $model->parent_id,
            $model->order,
        );
    }

    public function getId()
    {
        return $this->getNodeId();
    }

    public function getParentId()
    {
        return $this->getParentNodeId();
    }

    //    public function getType()
    //    {
    //        return $this->type;
    //    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    // Extra info when dealing with internal links
    //    public function getPageLabel()
    //    {
    //        return $this->page_label;
    //    }

    public function getUrl()
    {
        return $this->url;
    }

    public function isOffline(): bool
    {
        return $this->status != MenuItemStatus::online;
    }

    public function getAdminUrlLabel(): string
    {
        return $this->adminUrlLabel;
    }
}
