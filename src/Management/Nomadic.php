<?php

namespace Thinktomorrow\Chief\Management;

use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;

trait Nomadic
{
    public function nomadicCan($verb)
    {
        if($this->indexCollection()->count() > 0 && in_array($verb, ['create', 'store']))
        {
            throw NotAllowedManagerRoute::create($this);
        }

        if(! auth()->guard('chief')->user()->hasRole('developer') && in_array($verb, ['create', 'store', 'delete']) ){
            throw NotAllowedManagerRoute::notAllowedVerb($verb, $this);
        }
    }
}
