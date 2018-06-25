<?php

namespace Thinktomorrow\Chief\Authorization;

use Illuminate\Support\Collection;

class AuthorizationDefaults
{
    public static function roles(): Collection
    {
        return collect([

            // full access, even to application logic stuff
            'developer' => ['role', 'permission', 'user', 'page', 'disable-user', 'squanto', 'audit'],

            // Manages everything, including users
            'admin' => ['user', 'page', 'disable-user', 'view-squanto', 'audit'],

            // Writes and edits content
            'author' => ['page', 'view-squanto'],
        ]);
    }

    public static function permissions(): Collection
    {
        return collect([
            'view-permission',
            'create-permission',
            'update-permission',
            'delete-permission',

            'view-role',
            'create-role',
            'update-role',
            'delete-role',

            'view-user',
            'create-user',
            'update-user',
            'delete-user',
            'disable-user',

            'view-page',
            'create-page',
            'update-page',
            'delete-page',

            'view-squanto',
            'create-squanto',
            'update-squanto',
            'delete-squanto',

            'view-audit',
            // these don't do anything but the command fails if they don't exist (NEEDS FIXING)
            'create-audit',
            'update-audit',
            'delete-audit',
        ]);
    }
}
