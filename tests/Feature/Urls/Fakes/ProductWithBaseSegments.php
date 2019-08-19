<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls\Fakes;

use Thinktomorrow\Chief\Pages\Page;

class ProductWithBaseSegments extends Page
{
    protected static $managedModelKey = 'products_with_base';

    protected static $baseUrlSegment = [
        'nl' => 'producten',
        'en' => 'products',
    ];
}
