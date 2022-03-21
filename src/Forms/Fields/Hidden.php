<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

class Hidden extends Component implements Field
{
    protected string $view = 'chief-form::fields.hidden';
    protected string $windowView = 'chief-form::fields.hidden-window';
}
