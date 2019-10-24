<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls\Fakes;

class ProductFakeWithBaseSegments extends ProductFake
{
    protected static $managedModelKey = 'products_with_base';

    protected static $baseUrlSegment = [
        'nl' => 'producten',
        'en' => 'products',
    ];
}
