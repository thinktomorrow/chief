<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Management;

class Register
{
    /** @var array */
    private $registrations = [];

    public function __construct(array $registrations = [])
    {
        foreach ($registrations as $registration) {
            $this->push($registration);
        }
    }

    public function register($key, $class, $model, array $tags = [])
    {
        $this->push(new Registration($key, $class, $model, $tags));

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

    /**
     * Filter registrations by callback function
     *
     * @param callable $callback
     * @return Register
     */
    public function filter(callable $callback): self
    {
        if( ! is_callable($callback)) return new static($this->registrations);

        $registrations = $this->registrations;

        foreach ($registrations as $k => $registration) {
            if(!! call_user_func($callback, $registration)) {
                unset($registrations[$k]);
            }
        }

        return new static($registrations);
    }

    public function filterByKey(string $key): self
    {
        return $this->filterBy('key', $key);
    }

    public function filterByClass(string $class): self
    {
        return $this->filterBy('managerClass', $class);
    }

    public function filterByModel(string $class): self
    {
        return $this->filterBy('modelClass', $class);
    }

    public function filterByTag($tag): self
    {
        try{
            return $this->filterBy('tags', (array) $tag);
        }
        catch(NonRegisteredManager $e){
            return new static();
        }
    }

    public function rejectByKey(string $key): self
    {
        return $this->filterBy('key', $key, 'reject');
    }

    public function rejectByClass(string $class): self
    {
        return $this->filterBy('managerClass', $class, 'reject');
    }

    public function rejectByModel(string $class): self
    {
        return $this->filterBy('modelClass', $class, 'reject');
    }

    private function filterBy(string $key, $value, $type = 'filter'): self
    {
        $registrations = $this->registrations;

        foreach ($registrations as $k => $registration) {
            $containsValue = $registration->has($key, $value);

            if ($type == 'filter' && ! $containsValue) {
                unset($registrations[$k]);
            }

            if ($type == 'reject' && $containsValue) {
                unset($registrations[$k]);
            }
        }

        if ($type == 'filter') {
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
     * @param mixed $value
     * @param $registrations
     * @throws NonRegisteredManager
     */
    private function registrationMustExistConstraint(string $key, $value, $registrations): void
    {
        if (empty($registrations) && count($registrations) != $this->registrations) {
            throw new NonRegisteredManager('No manager found for ' . $key . ' [' . print_r($value) . ']. Did you perhaps forgot to register the manager?');
        }
    }
}
