<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasCharacterCount;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasPrependAppend;

class Number extends Component implements Field
{
    use HasCharacterCount;
    use HasPrependAppend;

    protected string $view = 'chief-forms::fields.number';
    protected string $windowView = 'chief-forms::fields.text-window';
}
