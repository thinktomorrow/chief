<?php

namespace Thinktomorrow\Chief\TableNew\Filters\Concerns;

trait CanBeApplied
{
    public function isApplicable($filterValue): bool
    {
        return true;
    }
}
