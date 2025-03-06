<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Sections;

use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\HasBookmark;

interface Section extends Fragment, HasBookmark
{
    /**
     * List of allowed fragments for this section fragment
     */
    public function allowedFragments(): array;
}
