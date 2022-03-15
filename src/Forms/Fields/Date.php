<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasStep;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasMinMax;

class Date extends Component implements Field
{
    use HasMinMax;
    use HasStep;

    protected string $view = 'chief-forms::fields.date';
    protected string $windowView = 'chief-forms::fields.date-window';
}
