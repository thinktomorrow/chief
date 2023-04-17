<?php

namespace Thinktomorrow\Chief\Admin\Tags\Read;

use Illuminate\Support\Collection;

interface TagReadRepository
{
    public function getAll(): Collection;

    public function getAllGroups(): Collection;

    public function getAllForSelect(): array;

    public function getAllGroupsForSelect(): array;
}
