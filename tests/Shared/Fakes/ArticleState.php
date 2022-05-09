<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Thinktomorrow\Chief\ManagedModels\States\State\State;

enum ArticleState: string implements State
{
    case online = 'online';
    case offline = 'offline';

    public function getValueAsString(): string
    {
        return $this->value;
    }
}
