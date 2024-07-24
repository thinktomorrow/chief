<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Actions;

use Illuminate\Contracts\Support\Htmlable;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasCustomAttributes;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLabel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLocalizableProperties;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Table\Concerns\HasView;
use Thinktomorrow\Chief\TableNew\Actions\Concerns\HasEffect;
use Thinktomorrow\Chief\TableNew\Actions\Concerns\HasModal;
use Thinktomorrow\Chief\TableNew\Columns\Concerns\HasLink;
use Thinktomorrow\Chief\TableNew\Columns\Concerns\HasVisibility;

class BulkAction extends Action
{
    protected string $view = 'chief-table-new::actions.bulk-action';
}
