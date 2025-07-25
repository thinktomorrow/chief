<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasToggleDisplay;

class Boolean extends Component implements Field
{
    // use HasToggleDisplay;

    protected string $view = 'chief-form::fields.boolean';

    protected string $previewView = 'chief-form::previews.fields.boolean';

    protected ?string $optionLabel = null;

    protected ?string $optionDescription = null;

    public function __construct(string $key)
    {
        parent::__construct($key);

        // $this->showAsToggle();
        $this->default(false);
    }

    public function optionLabel(string $optionLabel): static
    {
        $this->optionLabel = $optionLabel;

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

    /**
     * Overrides the default preview label to use the optionLabel if set.
     */
    public function getPreviewLabel(): ?string
    {
        if (! $this->previewLabel) {
            if ($this->getOptionLabel()) {
                return $this->getOptionLabel();
            }

            return $this->getLabel();
        }

        return $this->previewLabel;
    }
}
