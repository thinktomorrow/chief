<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\DynamicAttributes\HasDynamicAttributes;
use Thinktomorrow\Chief\Modules\Module;

class NewsletterModuleFake extends Module
{
    use HasDynamicAttributes;

    public $dynamicKeys = ['dynamic_title'];

    protected static $managedModelKey = 'newsletters_fake';

}
