<?php

namespace Chief\Common\Relations;

use Illuminate\Support\Collection;

class RelatedCollection extends Collection
{
    public static function availableChildren(ActsAsParent $parent): self
    {
        $available_children_types = config('thinktomorrow.chief.relations.children', []);

        $collection = new static();

        foreach($available_children_types as $type) {
            $collection = $collection->merge((new $type)->all());
        }

        return $collection;
    }

    public function flattenForSelect()
    {
        return $this->map(function(ActsAsChild $child){
            return [
                'id' => $child->getRelationId(),
                'label' => $child->getRelationLabel(),
            ];
        });
    }

    public static function inflate(array $relations = [])
    {
        //
    }
}