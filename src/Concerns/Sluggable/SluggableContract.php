<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Concerns\Sluggable;

interface SluggableContract
{
    public static function findBySlug($slug);
}
