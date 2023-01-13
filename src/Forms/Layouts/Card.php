<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use Thinktomorrow\Chief\Forms\Concerns\HasLayoutType;

class Card extends Component
{
    use HasLayoutType;

    protected string $view = 'chief-form::layouts.card';
    protected string $windowView = 'chief-form::layouts.default-window';

    public function __construct(?string $id = null)
    {
        parent::__construct($id);

        $this->layoutType(LayoutType::default->value);
    }
}
