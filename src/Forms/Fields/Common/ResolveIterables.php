<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Common;

class ResolveIterables
{
    /**
     * Makes sure that any generators are resolved
     */
    public static function resolve(iterable $iterable): iterable
    {
        $flattened = null;

        foreach ($iterable as $entry) {
            if (is_iterable($entry)) {
                foreach ($entry as $_entry) {
                    $flattened[] = $_entry;
                }
            } else {
                $flattened[] = $entry;
            }
        }

        return $flattened;
    }
}
