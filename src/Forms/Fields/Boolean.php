<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasToggleDisplay;

class Boolean extends Component implements Field
{
    use HasToggleDisplay;

    protected string $view = 'chief-form::fields.boolean';
    protected string $windowView = 'chief-form::fields.select-window';
}
