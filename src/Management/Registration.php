<?php

namespace Thinktomorrow\Chief\Management;

class Registration
{
    private $key;
    private $managerClass;
    private $modelClass;
    private $tags;

    public function __construct(string $key, string $managerClass, string $modelClass, array $tags = [])
    {
        $this->key = $key;
        $this->managerClass = $managerClass;
        $this->modelClass = $modelClass;
        $this->tags = $tags;

        $this->validate();
    }

    public static function fromArray(array $registration)
    {
        return new static(...array_values($registration));
    }

    /**
     * Return the key of the first entry.
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * Return the manager class
     *
     * @return string
     */
    public function class(): string
    {
        return $this->managerClass;
    }

    /**
     * Return the model class
     *
     * @return string
     */
    public function model(): string
    {
        return $this->modelClass;
    }

    public function tags(): array
    {
        return $this->tags;
    }

    public function has(string $key, $value): bool
    {
        if($key == 'tags'){

            if(is_array($value)) return (count(array_intersect($this->tags, $value)) > 0);

            return in_array($value, $this->tags);
        }

        return $this->$key == $value;
    }

    private function validate()
    {
        if (!class_exists($this->managerClass)) {
            throw new \InvalidArgumentException('Manager class ['.$this->managerClass.'] is an invalid class reference. Please make sure the class exists.');
        }

        if (!class_exists($this->modelClass)) {
            throw new \InvalidArgumentException('Model class ['.$this->modelClass.'] is an invalid model reference. Please make sure the class exists.');
        }

        $manager = new \ReflectionClass($this->managerClass);
        if (! $manager->implementsInterface(ModelManager::class)) {
            throw new \InvalidArgumentException('Class ['.$this->managerClass.'] is expected to implement the ['.ModelManager::class.'] contract.');
        }
    }
}
