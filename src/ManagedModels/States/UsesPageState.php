<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States;

trait UsesPageState
{
    public function stateOf(string $key)
    {
        return $this->$key;
    }

    public function changeStateOf(string $key, $state)
    {
        $this->$key = $state;
    }
}
