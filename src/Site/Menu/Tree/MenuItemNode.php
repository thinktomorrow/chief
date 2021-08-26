<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu\Tree;

use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Vine\DefaultNode;
use Thinktomorrow\Vine\Node;

class MenuItemNode extends DefaultNode implements Node
{
    private MenuItem $model;
    private ?string $label;

    public function __construct(MenuItem $model)
    {
        parent::__construct([
            'id' => $model->id,
            'parent_id' => $model->parent_id,
            'order' => $model->order,
        ]);

        $this->model = $model;
        $this->label = $this->model->label;
    }

    public function getId()
    {
        return $this->model->id;
    }

    public function getParentId()
    {
        return $this->model->parent_id;
    }

    public function getType()
    {
        return $this->model->type;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    // Extra info when dealing with internal links
    public function getPageLabel()
    {
        return $this->model->page_label;
    }

    public function getUrl()
    {
        return $this->model->url();
    }

    public function getOrder()
    {
        return $this->model->order;
    }

    public function getOwnerType()
    {
        return $this->model->owner_type;
    }

    public function getOwnerId()
    {
        return $this->model->owner_id;
    }

    // TODO: is this used?
    public function isHiddenInMenu(): bool
    {
        return ! ! $this->model->hidden_in_menu;
    }

    public function isDraft(): bool
    {
        return ! ! $this->model->draft;
    }
}
