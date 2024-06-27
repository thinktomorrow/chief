<?php

namespace Thinktomorrow\Chief\Plugins\TranslationsExport\Document;

interface Line
{
    public function getReference(): string;

    public function getColumns(): array;
}
