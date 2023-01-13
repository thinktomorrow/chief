<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasOptions;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasToggleDisplay;

class Checkbox extends Component implements Field
{
    use HasOptions;
    use HasToggleDisplay;

    protected string $view = 'chief-form::fields.checkbox';
    protected string $windowView = 'chief-form::fields.select-window';
}
