<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;

interface Fragmentable extends ReferableModel, ManagedModel
{
    public function renderAdminFragment($owner, $loop, $fragments);

    public function renderFragment($owner, $loop, $fragments, $viewData): string;

    public function isFragment(): bool;

    public function setFragmentModel(FragmentModel $fragmentModel): self;

    public function fragmentModel(): FragmentModel;
}
