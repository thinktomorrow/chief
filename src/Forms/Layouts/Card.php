<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use Thinktomorrow\Chief\Forms\Concerns\HasLayoutType;

class Card extends Component
{
    use HasLayoutType;

    protected string $view = 'chief-form::layouts.card';
    protected string $windowView = 'chief-form::layouts.default-window';

    // protected LayoutType $type = LayoutType::grey;
}
