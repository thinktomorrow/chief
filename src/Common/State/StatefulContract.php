<?php

namespace Chief\Common\State;

interface StatefulContract
{
    public function state(): string;

    public function changeState($state);
}
