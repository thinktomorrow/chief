<?php

namespace Thinktomorrow\Chief\Common\Relations;

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
    
    public static function relationIds(Collection $collection): Collection
    {
        return $collection->map(function($entry){
            return $entry->getRelationId();
        });    
    }

    public function flattenForSelect()
    {
        return $this->map(function(ActsAsChild $child){
            return [
                'id'    => $child->getRelationId(),
                'label' => $child->getRelationLabel(),
                'group' => $child->getRelationGroup(),
            ];
        });
    }

    public function flattenForGroupedSelect(): Collection
    {
        $grouped = [];

        $this->flattenForSelect()->each(function($entry) use(&$grouped){
            if(isset($grouped[$entry['group']])){
                $grouped[$entry['group']]['values'][] = $entry;
            } else{
                $grouped[$entry['group']] = ['group' => $entry['group'], 'values' => [$entry]];
            }
        });

        // We remove the group key as we need to have non-assoc array for the multiselect options.
        return collect(array_values($grouped));
    }

    public static function inflate(array $relateds = []): self
    {
        if(count($relateds) == 1 && is_null(reset($relateds))) $relateds = [];

        return (new static($relateds))->map(function($related){

            list($type,$id) = explode('@', $related);
            return (new $type)->find($id);

        });
    }
}