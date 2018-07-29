<?php

namespace Thinktomorrow\Chief\App\Console;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Authorization\AuthorizationDefaults;
use Illuminate\Database\Eloquent\Factory as ModelFactory;
use Illuminate\Support\Facades\Artisan;

class RefreshDatabase extends BaseCommand
{
    protected $signature = 'chief:refresh {--force}';
    protected $description = 'This will clear the entire database and reseed with development defaults';

    public function handle()
    {
        if (app()->environment() != 'local' && !$this->option('force')) {
            throw new \Exception('Aborting. This command is dangerous and only meant for your local environment.');
        }

        if (app()->environment() != 'local' && $this->option('force')) {
            if (!$this->confirm('You are about to force refresh the database in the '.app()->environment().' environment! ARE YOU SURE?')) {
                $this->info('aborting.');
                return;
            }

            if (!$this->confirm('ARE YOU REALLY SURE? THIS DELETES EVERYTHING!!!!!')) {
                $this->info('pfew.');
                return;
            }
        }

        if ($this->option('force')) {
            $this->call('migrate:fresh', ['--force' => true]);
        } else {
            $this->call('migrate:fresh');
        }

        // Include Our Chief factories for this command
        app(ModelFactory::class)->load(realpath(dirname(__DIR__).'/../database/factories'));

        $this->settingPermissionsAndRoles();
        $this->settingUsers();

        $this->info('Scaffolding some entries...');
        factory(Page::class, 5)->create();

        $this->info('Great. We\'re done here. NOW START HACKING!');
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

    private function settingUsers()
    {
        /**
         * The developer who is scaffolding this data is in charge of picking the default
         * password. This password is set for all dev accounts. On a staging or production
         * environment there should be an user invite sent instead.
         */
        $this->info('Now please set one password for all dev accounts.');
        $password = $this->askPassword();

        $admins = collect([
            ['Philippe', 'Damen', 'philippe@thinktomorrow.be', $password],
            ['Ben', 'Cavens', 'ben@thinktomorrow.be', $password],
            ['Johnny', 'Berkmans', 'johnny@thinktomorrow.be', $password],
            ['Json', 'Voorhees', 'json@thinktomorrow.be', $password],
        ]);

        $admins->each(function ($admin) {
            $this->createUser($admin[0], $admin[1], $admin[2], $admin[3], 'developer');
            $this->info('Added '.$admin[0].' as developer role with your provided password.');
        });
    }
}
