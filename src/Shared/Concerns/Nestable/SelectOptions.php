<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;

class SelectOptions
{
    public function getParentOptions(NestedTree $nestedTree, Model $model): array
    {
        $locale = app()->getLocale();

        $modelChildrenIds = ($model && $model->getKey() && $nestedNode = $nestedTree->find(fn($node) => $node->getId() == $model->getKey()))
            ? $nestedNode->pluckChildNodes('getId')
            : [];

        return $nestedTree->remove(function(NestedNode $page) use($model, $modelChildrenIds){
            return ($page->getId() == $model->getKey() || in_array($page->getId(), $modelChildrenIds));
        })->pluck($model->getKeyName(), fn($node) => $node->getBreadCrumbLabel($locale));
    }
}
