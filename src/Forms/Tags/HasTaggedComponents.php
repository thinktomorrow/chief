<?php

namespace Thinktomorrow\Chief\Forms\Tags;

use Thinktomorrow\Chief\Forms\Concerns\HasComponents;

interface HasTaggedComponents extends HasComponents
{
    public function filterByTagged($tag): static;

    public function filterByNotTagged($tag): static;

    public function filterByUntagged(): static;
}
