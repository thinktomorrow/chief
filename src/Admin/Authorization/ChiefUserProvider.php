<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Authorization;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class ChiefUserProvider extends EloquentUserProvider implements UserProvider
{
    protected function newModelQuery($model = null)
    {
        return parent::newModelQuery($model);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (! $user->isEnabled()) {
            return false;
        }

        return parent::validateCredentials($user, $credentials);
    }
}
