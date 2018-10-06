<?php

namespace Thinktomorrow\Chief\Management;

class Managers
{
    /** @var Register */
    private $register;

    public function __construct(Register $register)
    {
        $this->register = $register;
    }

    public function findByKey($key, $id = null): ModelManager
    {
        $class = $this->register->filterByKey($key)->toClass();

        return $id
            ? $class::findManaged($id)
            : app($class);
    }
}