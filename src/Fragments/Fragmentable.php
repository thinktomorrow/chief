<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentModel;
use Thinktomorrow\Chief\Resource\FragmentResource;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\ViewableContract;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

interface Fragmentable extends FragmentResource, ViewableContract, ReferableModel
{
    public function renderAdminFragment($owner, $loop, $viewData = []);

    public function renderFragment($owner, $loop, $viewData = []): string;

    /**
     * Check if this fragmentable has a fragment model set. This
     * means that the fragment is persisted in the database.
     */
    public function hasFragmentModel(): bool;

    public function setFragmentModel(FragmentModel $fragmentModel): self;

    public function fragmentModel(): FragmentModel;

    /**
     * The unique id reference of this fragment. This refers to the fragment model id.
     */
    public function getFragmentId(): string;
}
