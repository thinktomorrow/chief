<?php

namespace Thinktomorrow\Chief\Table\Columns;

class Header extends ColumnItem
{
    protected string $view = 'chief-table::columns.header';

    public function __construct(string $key, string $label)
    {
        parent::__construct($key);

        $this->label($label);
    }

    public static function makeHeader(string|int $key, string $label): static
    {
        return new static((string) $key, $label);
    }
}
