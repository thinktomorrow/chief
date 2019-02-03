<?php

namespace Thinktomorrow\Chief\Concerns\Sluggable;

interface SluggableContract
{
    public static function findBySlug($slug);
}
