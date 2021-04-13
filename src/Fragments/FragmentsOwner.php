<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

interface FragmentsOwner extends ReferableModel, ManagedModel
{
    public function allowedFragments(): array;

    public function ownerModel(): Model;
}