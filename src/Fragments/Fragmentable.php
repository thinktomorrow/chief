<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\ViewableContract;

interface Fragmentable extends ManagedModel, ViewableContract
{
    public function renderAdminFragment($owner, $loop, $viewData = []);

    public function renderFragment($owner, $loop, $viewData = []): string;

    public function setFragmentModel(FragmentModel $fragmentModel): self;

    public function fragmentModel(): FragmentModel;
}
