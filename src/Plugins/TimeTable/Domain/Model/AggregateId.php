<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model;

trait AggregateId
{
    private string $id;

    final private function __construct()
    {
        //
    }

    public static function fromString(string $id): static
    {
        if ($id === '') {
            throw new \DomainException('Aggregate id string cannot be empty.');
        }

        $aggregateId = new static;
        $aggregateId->id = $id;

        return $aggregateId;
    }

    public function get(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function equals($other): bool
    {
        return get_class($other) === get_class($this)
            && (string) $this === (string) $other;
    }
}
