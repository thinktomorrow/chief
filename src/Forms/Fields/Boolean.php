<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Closure;

class Boolean extends Component implements Field
{
    protected string $view = 'chief-form::fields.boolean';

    protected string $previewView = 'chief-form::previews.fields.boolean';

    protected ?string $optionLabel = null;

    protected ?string $optionDescription = null;

    public function __construct(string $key)
    {
        parent::__construct($key);

        $this->defaultOff();
    }

    public function default(int|bool|array|string|Closure|null $default): static
    {
        if (! is_bool($default)) {
            throw new \InvalidArgumentException('Default value for Boolean field must be a boolean.');
        }

        return parent::default($default);
    }

    public function defaultOff(): static
    {
        return $this->default(false);
    }

    public function defaultOn(): static
    {
        return $this->default(true);
    }

    public function optionLabel(string $optionLabel): static
    {
        $this->optionLabel = $optionLabel;

        $this->previewLabel($optionLabel);

        return $this;
    }

    public function getOptionLabel(): ?string
    {
        return $this->optionLabel;
    }

    public function optionDescription(string $optionDescription): static
    {
        $this->optionDescription = $optionDescription;

        return $this;
    }

    public function getOptionDescription(): ?string
    {
        return $this->optionDescription;
    }
}
