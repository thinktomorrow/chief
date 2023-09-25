<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasMultiple;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasOptions;

class Select extends Component implements Field
{
    use HasMultiple;
    use HasOptions;

    protected string $view = 'chief-form::fields.select';
    protected string $windowView = 'chief-form::fields.select-window';
}
