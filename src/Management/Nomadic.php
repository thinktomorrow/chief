<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Management;

use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;

trait Nomadic
{
    public function nomadicCan($verb)
    {
        if (in_array($verb, ['create', 'store', 'delete']) && ! auth()->guard('chief')->user()->hasRole('developer')) {
            throw NotAllowedManagerRoute::notAllowedVerb($verb, $this);
        }

        if (in_array($verb, ['create', 'store']) && $this->indexCollection()->count() > 0) {
            throw NotAllowedManagerRoute::create($this);
        }
    }
}
