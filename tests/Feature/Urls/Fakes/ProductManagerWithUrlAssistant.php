<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls\Fakes;

use Thinktomorrow\Chief\Management\Assistants\UrlAssistant;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;

class ProductManagerWithUrlAssistant extends ManagerFake
{
    protected $assistants = [
        'url' => UrlAssistant::class,
    ];
}