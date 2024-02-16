<?php

namespace Thinktomorrow\Chief\TableNew\UI\Helpers;

class TableColumn
{
    public static function image(string $imageUrl): string
    {
        return '<img src="' . $imageUrl .'" />';
    }
}
