<?php

use Illuminate\Support\Collection;
use Illuminate\Database\Migrations\Migration;

class AddMissingPermissions extends Migration
{
    public function up()
    {
        $permissions = [
            'archive-page',
        ];

        foreach($permissions as $permissionName)
        {
            Artisan::call('chief:permission', ['name' => $permissionName]);
        }

        $this->roles()->each(function ($defaultPermissions, $roleName) {
            Artisan::call('chief:role', ['name' => $roleName, '--permissions' => implode(',', $defaultPermissions)]);
        });
    }

    public function roles(): Collection
    {
        return collect([

            'developer' => ['archive-page'],

            'admin' => ['archive-page'],

            'author' => ['archive-page'],
        ]);
    }
}
