<?php

namespace Thinktomorrow\Chief\Table\UI\Helpers;

class TableColumn
{
    public static function image(string $imageUrl): string
    {
        return '<img src="' . $imageUrl .'" />';
    }
}
