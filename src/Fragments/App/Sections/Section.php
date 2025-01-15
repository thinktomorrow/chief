<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Sections;

use Thinktomorrow\Chief\Fragments\Fragment;

interface Section extends Fragment, HasBookmark
{
    /**
     * List of allowed fragments for this section fragment
     */
    public function allowedFragments(): array;
}
