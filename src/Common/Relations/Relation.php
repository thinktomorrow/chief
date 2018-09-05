<?php

namespace Thinktomorrow\Chief\Common\Relations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\PageSets\PageSetReference;
use Thinktomorrow\Chief\PageSets\StoredPageSetReference;
use Thinktomorrow\Chief\Modules\Module;
use Illuminate\Support\Facades\DB;

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

        return $relations->map(function ($relation) use ($parent_type, $parent_id) {
            $child = (new $relation->child_type)->find($relation->child_id);

            if (!$child) {

                // It could be that the child itself is soft-deleted, if this is the case, we will ignore it and move on.
                if ((!method_exists((new $relation->child_type), 'trashed')) || ! (new $relation->child_type)->onlyTrashed()->find($relation->child_id)) {
                    // If we cannot retrieve it then he collection type is possibly off, this is a database inconsistency and should be addressed
                    throw new \DomainException('Corrupt relation reference. Related child ['.$relation->child_type.'@'.$relation->child_id.'] could not be retrieved for parent [' . $parent_type.'@'.$parent_id.']. Make sure the collection type matches the class type.');
                }

                return null;
            }

            $child->relation = $relation;

            return $child;
        })

        // In case of soft-deleted entries, this will be null and should be ignored. We make sure that keys are reset in case of removed child
        ->reject(function ($child) {
            return is_null($child);
        })
        ->values();
    }

    public static function availableChildrenOnlyModules(Collection $collection): Collection
    {
        return $collection->reject(function($item){
            if ($item instanceof Page || $item instanceof StoredPageSetReference) {
                return true;
            }
        });
    }

    public static function availableChildrenOnlyPages(Collection $collection): Collection
    {
        return $collection->filter(function($item){
            if ($item instanceof Page) {
                return true;
            }
        });
    }

    public static function availableChildrenOnlyPageSets(): Collection
    {
        // We want a regular collection, not the database one so we inject it into a regular one.
        $stored_pagesets = collect(StoredPageSetReference::all()->keyBy('key')->all());
        $all_pagesets    = PageSetReference::all();

        return $all_pagesets->merge($stored_pagesets);
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
        
        // Merging the results of all the pages and all the modules, then filter by the config
        // This prevents us from having duplicates and also reduces the query load.
        $collection = array_merge(Page::all()->all(), Module::all()->all());
        $collection = collect($collection)->filter(function($page) use($available_children_types){
            return in_array(get_class($page), $available_children_types); 
        });

        return $collection->reject(function ($item) use ($parent) {
            if ($item instanceof Page) {
                return $item->id == $parent->id;
            }

            return false;
        });
    }

    public function delete()
    {
        return static::where('parent_type',$this->parent_type)
                ->where('parent_id',$this->parent_id)
                ->where('child_type',$this->child_type)
                ->where('child_id',$this->child_id)
                ->delete();
    }

    public static function deleteRelationsOf($type, $id)
    {
        $relations = static::where(function($query) use($type, $id){
            return $query->where('parent_type', $type)
                         ->where('parent_id', $id);
        })->orWhere(function($query) use($type, $id){
            return $query->where('child_type', $type)
                ->where('child_id', $id);
        })->get();

        foreach($relations as $relation) {
            $relation->delete();
        }
    }
}
