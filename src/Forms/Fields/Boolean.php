<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

class Boolean extends Component implements Field
{
    use hasToggleDisplay;

    protected string $view = 'chief-form::fields.boolean';
    protected string $windowView = 'chief-form::fields.select-window';
}
