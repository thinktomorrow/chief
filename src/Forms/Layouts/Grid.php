<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use Thinktomorrow\Chief\Forms\Concerns\HasColumns;

class Grid extends Component
{
    use HasColumns;

    protected string $view = 'chief-form::layouts.grid';
    protected string $windowView = 'chief-form::layouts.default-window';
}
