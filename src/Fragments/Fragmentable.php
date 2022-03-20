<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Resource\FragmentResource;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\ViewableContract;

interface Fragmentable extends FragmentResource, ViewableContract
{
    public function renderAdminFragment($owner, $loop, $viewData = []);

    public function renderFragment($owner, $loop, $viewData = []): string;

    public function setFragmentModel(FragmentModel $fragmentModel): self;

    public function fragmentModel(): FragmentModel;
}
