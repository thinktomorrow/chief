<?php

namespace Thinktomorrow\Chief\App\Console;

use Illuminate\Support\Facades\Artisan;
use Thinktomorrow\Chief\Admin\Authorization\AuthorizationDefaults;

class CreateAdmin extends BaseCommand
{
    protected $signature = 'chief:admin {--dev}';
    protected $description = 'Create a new chief admin user';

    public function handle(): void
    {
        $this->settingPermissionsAndRoles();

        $firstname = null;
        $lastname = null;

        while (! $firstname) {
            $firstname = $this->ask('Firstname');
        }

        while (! $lastname) {
            $lastname = $this->ask('Lastname');
        }

        $email = $this->ask('Email');

        $password = $this->askPassword();

        if ($this->option('dev')) {
            $role = 'developer';
        } else {
            $role = 'admin';
        }

        $this->createUser($firstname, $lastname, $email, $password, [$role]);

        $this->info($firstname . ' ' . $lastname . ' succesfully added as admin user.');
    }

    private function settingPermissionsAndRoles(): void
    {
        AuthorizationDefaults::permissions()->each(function ($permissionName) {
            Artisan::call('chief:permission', ['name' => $permissionName]);
        });

        AuthorizationDefaults::roles()->each(function ($defaultPermissions, $roleName) {
            Artisan::call('chief:role', ['name' => $roleName, '--permissions' => implode(',', $defaultPermissions)]);
        });

        $this->info('Default permissions and roles');
    }
}
