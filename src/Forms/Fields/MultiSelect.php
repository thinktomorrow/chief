<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasTaggable;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasGroupedOptions;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasMultiple;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasPairedOptions;

class MultiSelect extends Component implements Field
{
    use HasMultiple;
    use HasGroupedOptions;
    use HasTaggable;
    use HasPairedOptions;

    protected string $view = 'chief-form::fields.multiselect';
    protected string $windowView = 'chief-form::fields.select-window';
}
