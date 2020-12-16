<?php

namespace Thinktomorrow\Chief\Tests\Feature\Media\Fakes;

use Thinktomorrow\Chief\Modules\Module;

class MediaModule extends Module
{
    protected static $managedModelKey = 'mediamodule';

    public $labelSingular = 'module';
    public $labelPlural = 'module\'s';
}
