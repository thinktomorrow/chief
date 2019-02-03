<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Thinktomorrow\Chief\Management\ManagementDefaults;

class ManagerFakeWithoutDelete extends ManagerFake
{
    public function route($verb): ?string
    {
        if ($verb == 'delete') {
            return null;
        }

        return parent::route($verb);
    }
}
