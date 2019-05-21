<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Pages\Page;

class ProductPageFake extends Page
{
    protected static $baseUrlSegment = 'products';
}
