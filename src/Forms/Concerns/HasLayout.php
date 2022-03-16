<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

use Thinktomorrow\Chief\Forms\Layouts\Layout;

trait HasLayout
{
    protected Layout $layout = Layout::card;

    public function getLayout(): Layout
    {
        return $this->layout;
    }

    public function showAsCard(): static
    {
        $this->layout = Layout::card;

        return $this;
    }

    public function showAsBlank(): static
    {
        $this->layout = Layout::blank;

        return $this;
    }

    public function showAsGard(): static
    {
        return $this->showAsCard();
    }


}
