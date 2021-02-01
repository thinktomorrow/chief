<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Old\Pages;

use Thinktomorrow\Chief\Legacy\Pages\Page;

class Single extends Page
{
    protected static $managedModelKey = 'singles';

    public $labelSingular = 'pagina';
    public $labelPlural = 'pagina\'s';
}
