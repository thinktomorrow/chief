<?php

namespace Thinktomorrow\Chief\Forms\Layouts\Concerns;

trait HasFormDisplay
{
    /**
     * How the form is displayed on the page. Options are:
     * - window: the form is displayed in a window and edits are done via dialog
     * - blank: same as window but display is without window layout
     * - inline: the form is displayed as an inline form in window
     */
    protected string $formDisplay = 'window';

    public function displayAsWindowForm(): static
    {
        return $this->setFormDisplay('window');
    }

    public function displayAsTransparentForm(): static
    {
        return $this->setFormDisplay('transparent');
    }

    public function displayAsInlineForm(): static
    {
        return $this->setFormDisplay('inline');
    }

    public function displayAsCompactForm(): static
    {
        return $this->setFormDisplay('compact');
    }

    public function setFormDisplay(string $formDisplay): static
    {
        $this->formDisplay = $formDisplay;

        return $this;
    }

    public function getFormDisplay(): string
    {
        return $this->formDisplay;
    }
}
