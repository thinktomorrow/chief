<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Management;

class Register
{
    /** @var array */
    private $registrations = [];

    public function __construct(array $registrations = [])
    {
        foreach($registrations as $registration) {
            $this->push($registration);
        }
    }

    public function register($key, $class, $model)
    {
        $this->push( new Registration($key, $class, $model) );

        return $this;
    }

    private function push(Registration $registration)
    {
        $this->registrations[$registration->key()] = $registration;
    }

    public function all()
    {
        return $this->registrations;
    }

    public function first(): Registration
    {
        return array_first($this->registrations);
    }

    public function filterByKey(string $key): self
    {
        return $this->filter('key', $key);
    }

    public function filterByClass(string $class): self
    {
        return $this->filter('managerClass', $class);
    }

    public function filterByModel(string $class): self
    {
        return $this->filter('modelClass', $class);
    }

    public function rejectByKey(string $key): self
    {
        return $this->filter('key', $key, 'reject');
    }

    public function rejectByClass(string $class): self
    {
        return $this->filter('managerClass', $class, 'reject');
    }

    public function rejectByModel(string $class): self
    {
        return $this->filter('modelClass', $class, 'reject');
    }

    private function filter(string $key, $value, $type = 'filter'): self
    {
        $registrations = $this->registrations;

        foreach($registrations as $k => $registration)
        {
            $containsValue = $registration->has($key, $value);

            if($type == 'filter' && ! $containsValue) {
                unset($registrations[$k]);
            }

            if($type == 'reject' && $containsValue) {
                unset($registrations[$k]);
            }
        }

        if($type == 'filter') {
            $this->registrationMustExistConstraint($key, $value, $registrations);
        }

        return new static($registrations);
    }

    /**
     * Filtering on key or class is expected to contain one entry.
     * If not we should protect the application and warn the developer that a
     * manager was not registered properly
     *
     * @param string $key
     * @param $value
     * @param $registrations
     * @throws NonRegisteredManager
     */
    private function registrationMustExistConstraint(string $key, $value, $registrations): void
    {
        if (empty($registrations) && count($registrations) != $this->registrations) {
            throw new NonRegisteredManager('No manager found for ' . $key . ' [' . $value . ']. Did you perhaps forgot to register the manager?');
        }
    }
}