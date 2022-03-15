<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

class Hidden extends Component implements Field
{
    protected string $view = 'chief-forms::fields.hidden';
    protected string $windowView = 'chief-forms::fields.hidden-window';
}
