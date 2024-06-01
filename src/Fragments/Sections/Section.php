<?php
declare(strict_types=1);

namespace Sections;

use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Sections\HasBookmark;

interface Section extends Fragment, HasBookmark
{
    /**
     * List of allowed fragments for this section fragment
     */
    public function allowedFragments(): array;
}
