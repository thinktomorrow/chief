<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

trait AllowsOptions
{
    /** @var array */
    protected $options = [];

    /** @var mixed */
    protected $selected;

    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function selected($selected)
    {
        $this->selected = $selected;

        return $this;
    }

    public function getSelected()
    {
        return $this->selected;
    }
}
