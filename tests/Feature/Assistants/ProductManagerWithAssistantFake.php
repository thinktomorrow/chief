<?php

namespace Thinktomorrow\Chief\Tests\Feature\Assistants;

use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;

class ProductManagerWithAssistantFake extends ManagerFake
{
    protected $assistants = [
        'fake-assistant' => FakeAssistantWithFields::class,
    ];
}
