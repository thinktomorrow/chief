<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Admin\Users\User;

final class DeleteUser
{
    public function handle(User $user): void
    {
        DB::transaction(function () use ($user): void {
            DB::table('chief_password_resets')
                ->where('email', $user->email)
                ->delete();

            $user->delete();
        });
    }
}
