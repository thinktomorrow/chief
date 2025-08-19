<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasOptions;

class Checkbox extends Component implements Field
{
    use HasOptions;

    protected string $view = 'chief-form::fields.checkbox';

    protected string $previewView = 'chief-form::previews.fields.checkbox';
}
