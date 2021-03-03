<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

trait AllowsOptions
{
    protected array $options = [];

    /** @var mixed */
    protected $selected;

    /**
     * @return SelectField
     */
    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return SelectField
     */
    public function selected($selected): self
    {
        $this->selected = $selected;

        return $this;
    }

    public function getSelected()
    {
        return $this->selected;
    }
}
