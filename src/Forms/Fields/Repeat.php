<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

class Repeat extends Component implements Field
{
    protected string $view = 'chief-forms::fields.repeat';
    protected string $windowView = 'chief-forms::fields.repeat-window';

    public function elementView()
    {
        // index
    }
}
