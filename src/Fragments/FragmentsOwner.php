<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Database\Eloquent\Model;

interface FragmentsOwner
{
    public function allowedFragments(): array;

    public function ownerModel(): Model;
}
