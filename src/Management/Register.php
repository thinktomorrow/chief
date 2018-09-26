<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Management;

use ReflectionClass;

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

    public function register($key, $class)
    {
        $this->push([
            'key'      => $key,
            'class'    => $class,
        ]);

        return $this;
    }

    private function push(array $registration)
    {
        $this->validate($registration);

        $this->registrations[$registration['key']] = $registration;
    }

    public function all()
    {
        return $this->registrations;
    }

    public function filterByKey(string $key): self
    {
        return $this->filter('key', $key);
    }

    public function filterByClass(string $class): self
    {
        return $this->filter('class', $class);
    }

    public function rejectByKey(string $key): self
    {
        return $this->filter('key', $key, 'reject');
    }

    public function rejectByClass(string $class): self
    {
        return $this->filter('class', $class, 'reject');
    }

    private function filter(string $key, $value, $type = 'filter'): self
    {
        $registrations = $this->registrations;

        foreach($registrations as $k => $registration) {
            if($type == 'filter' && (!isset($registration[$key]) || $registration[$key] != $value)) {
                unset($registrations[$k]);
            }

            if($type == 'reject' && (isset($registration[$key]) && $registration[$key] == $value)) {
                unset($registrations[$k]);
            }
        }

        if($type == 'filter') {
            $this->registrationMustExistConstraint($key, $value, $registrations);
        }

        return new static($registrations);
    }

    public function toKeys(): array
    {
        return array_keys($this->registrations);
    }

    /**
     * Return the key of the first entry. This assumes you have filtered to
     * just one specific registration
     *
     * @return string
     */
    public function toKey(): string
    {
        return array_first($this->toKeys());
    }

    /**
     * Return the class of the first entry.
     * This assumes you have filtered to just one specific registration
     *
     * @return string
     */
    public function toClass(): string
    {
        $first = array_first($this->registrations);

        return $first['class'];
    }

    private function validate($registration)
    {
        if(!isset($registration['key']) || !isset($registration['class'])) {
            throw new \InvalidArgumentException('Invalid manager registration. Each registration requires a \'key\' and \'class\' entry.');
        }

        $class = $registration['class'];

        if(!class_exists($class)) {
            throw new \InvalidArgumentException('Class name ['.$class.'] is an invalid class reference. Please check if the class exists.');
        }

        $manager = new ReflectionClass($class);
        if( ! $manager->implementsInterface(ModelManager::class)) {
            throw new \InvalidArgumentException('Class ['.$class.'] is expected to implement the ['.ModelManager::class.'] contract.');
        }
    }

    /**
     *
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