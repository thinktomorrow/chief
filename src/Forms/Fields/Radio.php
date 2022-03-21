<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasOptions;

class Radio extends Component implements Field
{
    use HasOptions;

    protected string $view = 'chief-form::fields.radio';
    protected string $windowView = 'chief-form::fields.select-window';
}
