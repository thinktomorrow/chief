<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasCharacterCount;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasRedactorToolbar;

class Html extends Component implements Field
{
    use HasCharacterCount;
    use HasRedactorToolbar;

    protected string $view = 'chief-form::fields.html';

    protected string $previewView = 'chief-form::previews.fields.html';
}
