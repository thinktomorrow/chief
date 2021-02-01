<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\State;

interface StatefulContract
{
    public function stateOf(string $key);

    public function changeStateOf(string $key, $state);
}
