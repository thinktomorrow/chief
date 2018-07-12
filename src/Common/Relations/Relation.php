<?php

namespace Thinktomorrow\Chief\Common\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Relation extends Model
{
    public $timestamps = false;
    public $guarded = [];

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
                // If we cannot retrieve it then he collection type is possibly off, this is a database inconsistency and should be addressed
                throw new \DomainException('Corrupt relation reference. Related child ['.$relation->child_type.'@'.$relation->child_id.'] could not be retrieved for parent [' . $parent_type.'@'.$parent_id.']. Make sure the collection type matches the class type.');
            }

            $child->relation = $relation;
            return $child;
        });
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

        return $collection;
    }

    /**
     * TODO: move this to Collections helper class.
     *
     * Compile all relations into a flat list for select form field.
     * This includes a composite id made up of the type and id
     *
     * @return array
     */
    public static function flatten(array $relations = []): array
    {
        return [
            [
                'label' => 'Pagina\'s',
                'values' => Page::all()->map(function ($page) {
                    return ['composite_id' => $page->getOwnMorphClass().'@'.$page->id, 'label' => 'Pagina ' . teaser($page->title, 20, '...')];
                })->toArray(),
            ]
        ];
    }
}
