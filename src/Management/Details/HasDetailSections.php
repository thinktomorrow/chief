<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Management\Details;

trait HasDetailSections
{
    /**
     * Custom sections for the index listing such as sidebar info, filters, search, title and so on.
     */
    public static function sections(): DetailSections
    {
        return new DetailSections();
    }
}
