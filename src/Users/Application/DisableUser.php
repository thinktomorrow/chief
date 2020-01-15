<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Users\Application;

use Thinktomorrow\Chief\Users\User;

class DisableUser
{
    public function handle(User $user)
    {
        $user->disable();
    }
}
