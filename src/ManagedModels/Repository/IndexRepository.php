<?php

namespace Thinktomorrow\Chief\ManagedModels\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IndexRepository
{
    // TODO: extend with filter functionality (move filters logic and such from CrudAssistant)
    // TODO: extend with sorting and pagination options (towards livewire)

    public function getResults(): Collection;

    public function getNestableResults(): Collection;

    public function getPaginatedResults(): LengthAwarePaginator;
}
