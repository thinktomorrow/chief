<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values;

class Slot
{
    private ?Hour $from;
    private ?Hour $until;

    private function __construct()
    {
    }

    public static function make(?Hour $from, ?Hour $until): static
    {
        $model = new static();

        $model->from = $from;
        $model->until = $until;

        return $model;
    }

    public function getFrom(): ?Hour
    {
        return $this->from;
    }

    public function getUntil(): ?Hour
    {
        return $this->until;
    }

    public function getAsString(): string
    {
        return $this->from?->getFormat('H:i') .'-' . $this->until?->getFormat('H:i');
    }
}
