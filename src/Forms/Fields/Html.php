<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasCharacterCount;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasRedactorToolbar;

class Html extends Component implements Field
{
    use HasRedactorToolbar;

    protected string $view = 'chief-forms::fields.html';
    protected string $windowView = 'chief-forms::fields.html-window';
}
