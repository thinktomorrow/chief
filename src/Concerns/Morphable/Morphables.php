<?php

namespace Thinktomorrow\Chief\Concerns\Morphable;

use Illuminate\Database\Eloquent\Relations\Relation;

class Morphables
{
    public static function instance(string $key, $attributes = [])
    {
        $class = $key;

        // We assume the key is the full namespaced class or a string altered by the relation morph map.
        if ($morphedModel = Relation::getMorphedModel($key)) {
            $class = $morphedModel;
        }

        if (!class_exists($class)) {
            throw new NotFoundMorphKey('No class found by morphkey [' . $class . ']. Make sure that the morphkey is a valid class reference.');
        }

        return new $class($attributes);
    }
}
