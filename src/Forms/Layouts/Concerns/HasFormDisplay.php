<?php

namespace Thinktomorrow\Chief\Forms\Layouts\Concerns;

trait HasFormDisplay
{
    /**
     * How the form is displayed on the page. Options are:
     * - card: the form is displayed in a card and edits are done via dialog
     * - transparent: same as card but display is without card layout
     * - inline: the form is displayed as an inline form in window
     * - compact: the form is displayed as a compact form and edits are done via dialog
     */
    protected string $formDisplay = 'card';

    public function displayAsCardForm(): static
    {
        return $this->setFormDisplay('card');
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
