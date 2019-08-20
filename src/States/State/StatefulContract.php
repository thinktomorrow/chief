<?php

namespace Thinktomorrow\Chief\States\State;

interface StatefulContract
{
    public function state(): string;

    public function changeState($state);
}
