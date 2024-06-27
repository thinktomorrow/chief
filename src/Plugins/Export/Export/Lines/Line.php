<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export\Lines;

use Illuminate\Contracts\Support\Arrayable;

interface Line extends Arrayable
{
    public function getReference(): string;

    public function getColumns(): array;
}
