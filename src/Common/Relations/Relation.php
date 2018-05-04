<?php

namespace Chief\Common\Relations;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    public $timestamps = false;
    public $guarded = [];

    public static function parents($child_type, $child_id)
    {
        $relations = static::where('child_type', $child_type)
            ->where('child_id', $child_id)
            ->get();

        return $relations->map(function($relation){
            return (new $relation->parent_type)->find($relation->parent_id);
        });
    }

    public static function children($parent_type, $parent_id)
    {
        $relations = static::where('parent_type', $parent_type)
                      ->where('parent_id', $parent_id)
                      ->get();

        return $relations->map(function($relation){
            return (new $relation->child_type)->find($relation->child_id);
        });
    }
}