<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Morphable;

trait EloquentMorphableCreation
{
    public static function create(array $attributes = [])
    {
        if (! isset($attributes['morph_key'])) {
            $attributes['morph_key'] = (new static())->morphKey();
        }

        return static::query()->create($attributes);
    }
}
