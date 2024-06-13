<?php

namespace Thinktomorrow\Chief\Plugins\TranslationsExport\Document;

use Illuminate\Contracts\Support\Arrayable;

interface Line extends Arrayable
{
    public function getReference(): string;

    public function getColumns(): array;
}
