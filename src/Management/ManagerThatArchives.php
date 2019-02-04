<?php

namespace Thinktomorrow\Chief\Management;

use Illuminate\Support\Collection;

interface ManagerThatArchives
{
    public function isArchived(): bool;

    public function archive();

    public function unarchive();

    public function findAllArchived(): Collection;
}
