<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Pages\Page;

class ProductPageFake extends Page
{
    protected static $baseUrlSegment = 'products';

    public function renderView(): string
    {
        return 'product-page-fake-content';
    }

    public static function managedModelKey(): string
    {
        return 'products';
    }
}
