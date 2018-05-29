<?php

namespace Thinktomorrow\Chief\Common\Contracts;

interface SluggableContract
{
    public static function findBySlug($slug);
}
