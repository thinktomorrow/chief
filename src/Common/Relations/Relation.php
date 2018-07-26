<?php

namespace Thinktomorrow\Chief\Common\Relations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Relation extends Model
{
    public $timestamps = false;
    public $guarded = [];

    /**
     * Set the keys for a save update query.
     * We override the default save setup since we do not have a primary key in relation table.
     * There should however always be the parent and child references so we can use
     * those to target the record in database.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query->where('parent_type', $this->getMorphClass())
                ->where('parent_id', $this->getKey())
                ->where('child_type', $this->child_type)
                ->where('child_id', $this->child_id);

        return $query;
    }

    public static function parents($child_type, $child_id)
    {
        $relations = static::where('child_type', $child_type)
            ->where('child_id', $child_id)
            ->orderBy('sort', 'ASC')
            ->get();

        return $relations->map(function ($relation) {
            $parent = (new $relation->parent_type)->find($relation->parent_id);
            $parent->relation = $relation;
            return $parent;
        });
    }

    public static function children($parent_type, $parent_id)
    {
        $relations = static::where('parent_type', $parent_type)
            ->where('parent_id', $parent_id)
            ->orderBy('sort', 'ASC')
            ->get();

        return $relations->map(function ($relation) use($parent_type, $parent_id) {
            $child = (new $relation->child_type)->find($relation->child_id);

            if(!$child) {

                // It could be that the child itself is soft-deleted, if this is the case, we will ignore it and move on.
                if( ! (new $relation->child_type)->withTrashed()->find($relation->child_id)) {
                    // If we cannot retrieve it then he collection type is possibly off, this is a database inconsistency and should be addressed
                    throw new \DomainException('Corrupt relation reference. Related child ['.$relation->child_type.'@'.$relation->child_id.'] could not be retrieved for parent [' . $parent_type.'@'.$parent_id.']. Make sure the collection type matches the class type.');
                }

                return null;
            }

            $child->relation = $relation;

            return $child;

        })

        // In case of soft-deleted entries, this will be null and should be ignored. We make sure that keys are reset in case of removed child
        ->reject(function($child){ return is_null($child); })
        ->values();
    }

    /**
     * Fetch all available children instances
     *
     * @param ActsAsParent $parent
     * @return Collection
     */
    public static function availableChildren(ActsAsParent $parent): Collection
    {
        $available_children_types = config('thinktomorrow.chief.relations.children', []);
        $collection = collect([]);

        foreach ($available_children_types as $type) {
            $collection = $collection->merge((new $type())->all());
        }

        return $collection->reject(function($item) use($parent){
            return $item->id == $parent->id;
        });
    }
}
