<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use Thinktomorrow\Chief\Forms\Concerns\HasCollapsible;

class Card extends Component
{
    use HasCollapsible;

    protected string $view = 'chief-form::layouts.card';

    protected string $previewView = 'chief-form::previews.layouts.card';
}
