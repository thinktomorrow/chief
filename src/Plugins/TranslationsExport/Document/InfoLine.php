<?php

namespace Thinktomorrow\Chief\Plugins\TranslationsExport\Document;

class InfoLine implements Line
{
    private array $columns;

    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getReference(): string
    {
        return '';
    }

    public function toArray()
    {
        return $this->getColumns();
    }
}
