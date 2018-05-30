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

        return $relations->map(function ($relation) {
            $child = (new $relation->child_type)->find($relation->child_id);
            $child->relation = $relation;
            return $child;
        });
    }

    /**
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
                    return ['composite_id' => $page->getMorphClass().'@'.$page->id, 'label' => 'Pagina ' . teaser($page->title, 20, '...')];
                })->toArray(),
            ]
        ];
    }
}
