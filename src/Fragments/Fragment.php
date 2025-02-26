<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Resource\FragmentResource;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Vine\Node;

interface Fragment extends FragmentResource, Htmlable, Node, ReferableModel
{
    public function render(): View;

    public function renderInAdmin(): View;

    /**
     * The unique id reference of this fragment.
     * This also refers to the fragment model id.
     */
    public function getFragmentId(): string;

    /**
     * Check if this fragment has an eloquent model. This
     * means that the fragment is persisted in the database.
     */
    public function hasFragmentModel(): bool;

    public function setFragmentModel(FragmentModel $fragmentModel): self;

    public function getFragmentModel(): FragmentModel;
}
