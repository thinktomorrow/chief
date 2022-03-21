<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasCharacterCount;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasMinMax;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasPrependAppend;

class Number extends Component implements Field
{
    use HasCharacterCount;
    use HasPrependAppend;
    use HasMinMax;

    protected string $view = 'chief-form::fields.number';
    protected string $windowView = 'chief-form::fields.text-window';
}
