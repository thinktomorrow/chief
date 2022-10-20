<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\States\StateMachine\Stubs;

use Thinktomorrow\Chief\ManagedModels\States\State\State;

enum OnlineStateStub: string implements State
{
    case online = 'online';
    case offline = 'offline';

    public function getValueAsString(): string
    {
        return $this->value;
    }
}
