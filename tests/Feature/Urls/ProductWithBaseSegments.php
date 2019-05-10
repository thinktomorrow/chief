<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls;

use Thinktomorrow\Chief\Pages\Page;

class ProductWithBaseSegments extends Page
{
    protected static $baseUrlSegment = [
        'en' => 'products',
        'nl' => 'producten',
    ];
}