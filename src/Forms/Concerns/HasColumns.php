<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasColumns
{
    protected int $columns = 1;
    protected array $span = [1];

    public function columns(int $columns): static
    {
        $this->columns = $columns;

        return $this;
    }

    public function getColumns(): int
    {
        return $this->columns;
    }

    public function span(array $span): static
    {
        $this->span = $span;

        return $this;
    }

    public function getSpan(int $index = 0): int
    {
        if($index === 0) {
            return $this->span[0];
        }

        // dd($this->span[$index % count($this->span)]);

        return $this->span[$index % count($this->span)];
    }

    public function getColumnSpanStyle($span): string
    {
        switch($span . '/' . $this->columns) {
            case '1/2':
                return 'w-full sm:w-1/2';
            case '1/3':
                return 'w-full sm:w-1/3';
            case '2/3':
                return 'w-full sm:w-2/3';
            default:
                return 'w-full';
        }
    }
}
