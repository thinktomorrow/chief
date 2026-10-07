<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\ManagedModels\Events\ChiefActionCompleted;

class DisableUser
{
    public function handle(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $user->disable();
            event(ChiefActionCompleted::forModels('user.disabled', $user));
        });
    }
}
