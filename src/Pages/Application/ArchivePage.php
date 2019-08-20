<?php

namespace Thinktomorrow\Chief\States\Application;

use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\States\Archivable\ArchivableContract;

class ArchivePage
{
    public function handle(ArchivableContract $page)
    {
        (new PageState($page))->apply('archive');

        $page->save();

        event(new PageArchived($page->id));
    }
}
