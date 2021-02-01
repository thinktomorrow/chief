<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Sluggable;

interface SluggableContract
{
    public static function findBySlug($slug);
}
