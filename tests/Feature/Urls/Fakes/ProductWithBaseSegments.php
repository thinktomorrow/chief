<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls\Fakes;

use Thinktomorrow\Chief\Pages\Page;

class ProductWithBaseSegments extends Page
{
    protected static $baseUrlSegment = [
        'en' => 'products',
        'nl' => 'producten',
    ];
}