<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use Thinktomorrow\Chief\Forms\Concerns\HasCollapsible;
use Thinktomorrow\Chief\Forms\Concerns\HasLayoutVariant;

class Card extends Component
{
    use HasCollapsible;
    use HasLayoutVariant;

    protected string $view = 'chief-form::layouts.card';

    protected string $previewView = 'chief-form::layouts.default';

    public function __construct(?string $key = null)
    {
        parent::__construct($key);

        $this->layoutType(LayoutVariant::default->value);
    }
}
