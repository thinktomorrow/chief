<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasOptions;

class Checkbox extends Component implements Field
{
    use HasOptions;

    protected string $view = 'chief-forms::fields.checkbox';
    protected string $windowView = 'chief-forms::fields.select-window';
}
