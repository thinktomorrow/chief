<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Authorization;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Thinktomorrow\Chief\Admin\Users\User;

class ChiefUserProvider extends EloquentUserProvider implements UserProvider
{
    protected function newModelQuery($model = null)
    {
        return parent::newModelQuery($model);
    }

    public function retrieveByToken($identifier, $token)
    {
        $user = parent::retrieveByToken($identifier, $token);

        if (! $user || ($user instanceof User && ! $user->isEnabled())) {
            return null;
        }

        return $user;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if ($user instanceof User && ! $user->isEnabled()) {
            return false;
        }

        return parent::validateCredentials($user, $credentials);
    }
}
