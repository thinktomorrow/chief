<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\SimpleState;

use Thinktomorrow\Chief\ManagedModels\States\State\State;

enum SimpleState: string implements State
{
    case online = 'online';
    case offline = 'offline';
    case deleted = 'deleted';

    public const KEY = 'current_state';

    public function getValueAsString(): string
    {
        return $this->value;
    }
}
