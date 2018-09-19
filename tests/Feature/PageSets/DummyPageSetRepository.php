<?php

namespace Thinktomorrow\Chief\Tests\Feature\PageSets;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\PageSets\PageSet;

class DummyPageSetRepository
{
    public function all($limit = 100)
    {
        $pages = Page::limit($limit)->get();

        return new PageSet($pages);
    }
}