<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasTaggable;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasEloquentOptionsSync;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasGroupedOptions;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasMultiple;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasOptions;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;

class SelectList extends Component implements Field
{
    use HasEloquentOptionsSync;
    use HasGroupedOptions;
    use HasMultiple;
    use HasOptions;
    use HasTaggable;

    protected string $view = 'chief-form::fields.select-list';

    protected string $windowView = 'chief-form::fields.select-window';

    public function getMultiSelectFieldOptions(?string $locale = null): array
    {
        return PairOptions::convertOptionsToChoices($this->getOptions($locale));
    }
}
