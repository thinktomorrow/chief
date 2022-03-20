<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Contracts\View\View;

interface FragmentResource extends Resource
{
    public function adminView(): View;
}
