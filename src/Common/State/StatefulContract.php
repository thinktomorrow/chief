<?php

namespace Thinktomorrow\Chief\Common\State;

interface StatefulContract
{
    public function state(): string;

    public function changeState($state);
}
