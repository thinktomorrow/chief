<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use Thinktomorrow\Chief\Forms\Concerns\HasVisibilityState;

class AccordionItem extends Component
{
    use HasVisibilityState;

    protected string $view = 'chief-form::layouts.accordion-item';
    protected string $windowView = 'chief-form::layouts.default-window';
}
