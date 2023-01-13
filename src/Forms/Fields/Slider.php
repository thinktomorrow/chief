<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasMinMax;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasStep;

class Slider extends Component implements Field
{
    use HasMinMax;
    use HasStep;

    protected string $view = 'chief-form::fields.slider';
    protected string $windowView = 'chief-form::fields.text-window';
}
