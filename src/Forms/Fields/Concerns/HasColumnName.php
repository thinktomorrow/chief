<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasColumnName
{
    protected string $columnName;

    public function columnName(string $columnName): static
    {
        $this->columnName = $columnName;

        return $this;
    }

    public function getColumnName(): string
    {
        return $this->columnName;
    }
}
