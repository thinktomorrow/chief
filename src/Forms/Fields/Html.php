<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

// use Thinktomorrow\Chief\Forms\Fields\Concerns\HasRedactorToolbar;

class Html extends Component implements Field
{
    // use HasRedactorToolbar;

    protected string $view = 'chief-form::fields.html-editor';

    protected string $windowView = 'chief-form::previews.fields.html';

    public function editor(): static
    {
        $this->view = 'chief-form::fields.html-editor';

        return $this;
    }

    public function raw(): static
    {
        $this->view = 'chief-form::fields.html';

        return $this;
    }
}
