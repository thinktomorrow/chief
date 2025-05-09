<?php

namespace Thinktomorrow\Chief\Forms\Concerns;

interface HasComponents
{
    public function components(array $components): static;

    public function getComponents(): array;
}
