<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\State;

interface State
{
    public function getValueAsString(): string;
}
