<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\States\State;

interface StatefulContract
{
    public function stateOf(string $key);

    public function changeStateOf(string $key, $state);
}
