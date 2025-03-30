<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasLayout
{
    protected string $layout = 'card';

    public function getLayout(): string
    {
        return $this->layout;
    }

    public function showAsCard(): static
    {
        $this->layout = 'card';

        return $this;
    }

    public function showAsBlank(): static
    {
        $this->layout = 'blank';

        return $this;
    }
}
