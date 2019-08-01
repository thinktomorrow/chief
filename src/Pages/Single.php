<?php

namespace Thinktomorrow\Chief\Pages;
use Thinktomorrow\Chief\Concerns\Archivable\Archivable;

class Single extends Page
{
    protected static $managedModelKey = 'singles';

    public $labelSingular = 'pagina';
    public $labelPlural = 'pagina\'s';
}
