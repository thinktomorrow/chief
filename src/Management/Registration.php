<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Management;

class Registration
{
    private $key;
    private $managerClass;
    private $modelClass;
    private $tags;

    final public function __construct(string $managerClass, string $modelClass, array $tags = [])
    {
        $this->validate($managerClass, $modelClass);

        $this->key          = $modelClass::managedModelKey();
        $this->managerClass = $managerClass;
        $this->modelClass   = $modelClass;
        $this->tags         = $tags;
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
        if ($key == 'tags') {
            if (is_array($value)) {
                return (count(array_intersect($this->tags, $value)) > 0);
            }

            return in_array($value, $this->tags);
        }

        return $this->$key == $value;
    }

    private function validate($managerClass, $modelClass)
    {
        if (!class_exists($managerClass)) {
            throw new \InvalidArgumentException('Manager class ['.$managerClass.'] is an invalid class reference. Please make sure the class exists.');
        }

        if (!class_exists($modelClass)) {
            throw new \InvalidArgumentException('Model class ['.$modelClass.'] is an invalid model reference. Please make sure the class exists.');
        }

        $manager = new \ReflectionClass($managerClass);
        if (! $manager->implementsInterface(Manager::class)) {
            throw new \InvalidArgumentException('Class ['.$managerClass.'] is expected to implement the ['.Manager::class.'] contract.');
        }

        $model = new \ReflectionClass($modelClass);
        if (! $model->implementsInterface(ManagedModel::class)) {
            throw new \InvalidArgumentException('Class ['.$modelClass.'] is expected to implement the ['.ManagedModel::class.'] contract.');
        }
    }
}
