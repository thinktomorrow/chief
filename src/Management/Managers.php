<?php

namespace Thinktomorrow\Chief\Management;

use Illuminate\Support\Collection;

class Managers
{
    /** @var Register */
    private $register;

    public function __construct(Register $register)
    {
        $this->register = $register;
    }

    public function findByKey($key, $id = null): Manager
    {
        $registration = $this->register->filterByKey($key)->first();

        return $this->instance($registration, $id);
    }

    public function findByModel($model, $id = null): Manager
    {
        /**
         * If an instance is passed as model, we try to extract the class and id
         * ourselves and assume we can read the id value via the id property
         */
        if(null === $id && !is_string($model)) {

            $modelClass = get_class($model);

            $registration = $this->register->filterByModel($modelClass)->first();

            return $this->instanceModel($registration, $model);
        }

        $registration = $this->register->filterByModel($model)->first();

        return $this->instance($registration, $id);
    }

    /**
     * Enlist all details for a certain tag group of managed models
     *
     * @param $tag
     * @return Collection of Managers
     */
    public function findByTag($tag): Collection
    {
        $registrations = collect($this->register->filterByTag($tag)->all());

        return $registrations->map(function($registration){
            return $this->instance($registration);
        });
    }

    /**
     * Enlist all details for a certain tag group of managed models
     *
     * @param $tag
     * @return Collection of ManagedModelDetails
     */
    public function findDetailsByTag($tag): Collection
    {
        $registrations = collect($this->register->filterByTag($tag)->all());

        return $registrations->map(function($registration){
            return $this->instance($registration)->details();
        });
    }

    public function all(): Collection
    {
        $registrations = collect($this->register->all());

        return $registrations->map(function($registration){
            return $this->instance($registration);
        });
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

    /**
     * @param $registration
     * @param $model
     * @return mixed
     */
    private function instanceModel(Registration $registration, $model)
    {
        $managerClass = $registration->class();

        $manager = new $managerClass($registration);

        return $manager->manage($model);
    }
}
