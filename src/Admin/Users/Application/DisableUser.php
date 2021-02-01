<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Application;

use Thinktomorrow\Chief\Admin\Users\User;

class DisableUser
{
    public function handle(User $user)
    {
        $user->disable();
    }
}
