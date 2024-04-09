<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Database\Eloquent\Model;

interface ContextOwner
{
    public function activeContextId(string $locale): ?string;
}
