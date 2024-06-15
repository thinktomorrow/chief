<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Database\Eloquent\Model;

// TODO: no longer needed only for Page (ContextOwner?)
/**
 * @deprecated use Thinktomorrow\Chief\Fragments\ContextOwner instead
 */
interface FragmentsOwner
{
    public function allowedFragments(): array;

    public function ownerModel(): Model;
}
