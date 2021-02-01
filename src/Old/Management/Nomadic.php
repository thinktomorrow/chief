<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Old\Management;

use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;

trait Nomadic
{
    public function nomadicCan($verb)
    {
        if (in_array($verb, ['create', 'store', 'delete']) && !auth()->guard('chief')->user()->hasRole('developer')) {
            throw NotAllowedManagerAction::notAllowedAction($verb, $this->details()->key);
        }

        if (in_array($verb, ['create', 'store']) && $this->indexCollection()->count() > 0) {
            throw NotAllowedManagerAction::create($this->details()->key);
        }
    }
}
