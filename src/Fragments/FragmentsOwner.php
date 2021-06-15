<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;

interface FragmentsOwner extends ManagedModel
{
    public function allowedFragments(): array;

    public function ownerModel(): Model;
}
