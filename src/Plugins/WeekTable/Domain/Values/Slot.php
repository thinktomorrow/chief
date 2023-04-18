<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Domain\Values;

class Slot
{
    private \DateTime $from;
    private \DateTime $until;

    private function __construct()
    {
    }

    public static function make(\DateTime $from, \DateTime $until): static
    {
        $model = new static();

        $model->from = $from;
        $model->until = $until;

        return $model;
    }

    public function getFrom(): \DateTime
    {
        return $this->from;
    }

    public function getUntil(): \DateTime
    {
        return $this->until;
    }
}
