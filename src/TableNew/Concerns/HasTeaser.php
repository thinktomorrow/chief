<?php

namespace Thinktomorrow\Chief\TableNew\Concerns;

trait HasTeaser {

    protected ?array $tease = null;

    public function tease($max = null, $ending = null, $clean = ''): static
    {
        $this->tease = [
            'max' => $max,
            'ending' => $ending,
            'clean' => $clean,
        ];

        return $this;
    }

    protected function teaseValue($value): mixed
    {
        if(! $this->tease) return $value;

        return teaser($value, $this->tease['max'], $this->tease['ending'], $this->tease['clean']);
    }
}
