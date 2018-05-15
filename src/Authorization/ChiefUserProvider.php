<?php

namespace Chief\Authorization;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider;

class ChiefUserProvider extends EloquentUserProvider implements UserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $user = parent::retrieveByCredentials($credentials);

        return ($user->isEnabled()) ? $user : null;
    }
}
