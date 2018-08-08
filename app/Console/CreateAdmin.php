<?php

namespace Thinktomorrow\Chief\App\Console;

use Illuminate\Support\Facades\Artisan;
use Thinktomorrow\Chief\Authorization\AuthorizationDefaults;

class CreateAdmin extends BaseCommand
{
    protected $signature = 'chief:admin {--dev}';
    protected $description = 'Create a new chief admin user';

    public function handle()
    {
        $this->settingPermissionsAndRoles();

        $anticipations = $this->getAnticipations();

        $firstname = $this->anticipate('firstname', array_pluck($anticipations, 'firstname'));
        $anticipatedLastname = null;
        $lastname = $this->anticipate('lastname', array_pluck($anticipations, 'lastname'), $anticipatedLastname);

        $email = $this->ask('email', str_slug($firstname).'@thinktomorrow.be');

        $password = $this->askPassword();

        if ($this->option('dev')) {
            $role = 'developer';
        } else {
            $role = 'admin';
        }
        
        $this->createUser($firstname, $lastname, $email, $password, [$role]);
        
        $this->info($firstname.' '.$lastname. ' succesfully added as admin user.');
    }

    private function settingPermissionsAndRoles()
    {
        AuthorizationDefaults::permissions()->each(function ($permissionName) {
            Artisan::call('chief:permission', ['name' => $permissionName]);
        });

        AuthorizationDefaults::roles()->each(function ($defaultPermissions, $roleName) {
            Artisan::call('chief:role', ['name' => $roleName, '--permissions' => implode(',', $defaultPermissions)]);
        });
        
        $this->info('Default permissions and roles');
    }

    /**
     * We assume we are creating users for ourselves so we make this a bit easier to do
     * @return array
     */
    private function getAnticipations()
    {
        $anticipations = [
            [
                'firstname' => 'Ben',
                'lastname'  => 'Cavens',
                'email'     => 'ben@thinktomorrow.be',
            ],
            [
                'firstname' => 'Philippe',
                'lastname'  => 'Damen',
                'email'     => 'philippe@thinktomorrow.be',
            ],
            [
                'firstname' => 'Johnny',
                'lastname'  => 'Berkmans',
                'email'     => 'johnny@thinktomorrow.be',
            ],
            [
                'firstname' => 'Bob',
                'lastname'  => 'Dries',
                'email'     => 'bob@thinktomorrow.be',
            ],
        ];

        return $anticipations;
    }
}
