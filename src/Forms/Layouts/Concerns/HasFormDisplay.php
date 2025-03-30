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
        $this->formDisplay = 'window';

        return $this;
    }

    public function displayAsBlankForm(): static
    {
        $this->formDisplay = 'blank';

        return $this;
    }

    public function displayAsInlineForm(): static
    {
        $this->formDisplay = 'inline';

        return $this;
    }

    public function getFormDisplay(): string
    {
        return $this->formDisplay;
    }
}
