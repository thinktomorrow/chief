<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\PageState;

use Thinktomorrow\Chief\ManagedModels\States\State\State;

enum PageState: string implements State
{
    case draft = 'draft';
    case archived = 'archived';
    case deleted = 'deleted';
    case published = 'published';

    public const KEY = 'current_state';

    public function getValueAsString(): string
    {
        return $this->value;
    }
}
