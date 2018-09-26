<?php

namespace Thinktomorrow\Chief\Tests\Feature\Sets;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\PageSets\PageSet;
use Thinktomorrow\Chief\Sets\Set;

class DummySetRepository
{
    public function all($limit = 100)
    {
        $pages = Page::limit($limit)->get();

        return new Set($pages, 'all-pages');
    }
}
