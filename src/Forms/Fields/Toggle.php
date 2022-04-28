<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Closure;

class Toggle extends Checkbox
{
    protected string $view = 'chief-form::fields.toggle';
    protected array|Closure $options = [
        'true' => ''
    ];
}
