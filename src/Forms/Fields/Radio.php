<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasOptions;

class Radio extends Component implements Field
{
    use HasOptions;

    protected string $view = 'chief-forms::fields.radio';
    protected string $windowView = 'chief-forms::fields.select-window';
}