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
        $registration = $this->register->filterByKey($key)->first();

        return $this->instance($registration, $id);
    }

    public function findByModel($model, $id = null): ModelManager
    {
        $registration = $this->register->filterByModel($model)->first();

        return $this->instance($registration, $id);
    }

    /**
     * @param $registration
     * @param $id
     * @return mixed
     */
    private function instance(Registration $registration, $id = null)
    {
        $managerClass = $registration->class();

        $manager = new $managerClass($registration);

        return $id
            ? $manager->findManaged($id)
            : $manager;
    }
}