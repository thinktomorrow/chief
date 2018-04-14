<?php

namespace App\Console\Commands;

use Chief\Articles\Article;
use Chief\Roles\Permission;
use Chief\Roles\Role;
use Chief\Users\User;

class RefreshDatabase extends BaseCommand
{
    protected $signature = 'chief:db-refresh {--force}';
    protected $description = 'This will clear the entire database and reseed with development defaults';

    public function handle()
    {
        if(app()->environment() != 'local' && !$this->option('force'))
        {
            throw new \Exception('Aborting. This command is dangerous and only meant for your local environment.');
        }

        if(app()->environment() != 'local' && $this->option('force'))
        {
            if (!$this->confirm('You are about to force refresh the database in the '.app()->environment().' environment! ARE YOU SURE?')) {
                $this->info('aborting.');
                return;
            }

            if (!$this->confirm('ARE YOU REALLY SURE?')) {
                $this->info('pfew.');
                return;
            }
        }

        if($this->option('force')){
            $this->call('migrate:refresh', ['--force' => true]);
        }else{
            $this->call('migrate:refresh');
        }

        $this->settingPermissionsAndRoles();
        $this->settingUsers();

        $this->info('Scaffolding some entries...');
        factory(User::class, 10)->create();
        factory(Article::class, 10)->create();

        $this->info('Great. We\'re done here. NOW START HACKING!');
    }

    private function settingPermissionsAndRoles()
    {
        // Seed the default permissions
        foreach (Permission::defaultPermissions() as $perms) {
            Permission::firstOrCreate(['name' => $perms]);
        }

        // add default roles
        foreach (['superadmin', 'admin', 'user'] as $role) {
            $role = Role::firstOrCreate(['name' => trim($role)]);

            if ($role->name == 'superadmin') {
                // assign all permissions
                $role->syncPermissions(Permission::all());
            } else {
                // for others by default only read access
                $role->syncPermissions(Permission::where('name', 'LIKE', 'view_%')->get());
            }
        }

        $this->info('setting permissions and roles');
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
            ['Ben', 'Cavens', 'ben@thinktomorrow.be', $password],
            ['Philippe', 'Damen', 'philippe@thinktomorrow.be', $password],
            ['Bob', 'Dries', 'bob@thinktomorrow.be', $password],
            ['Johnny', 'Berkmans', 'johnny@thinktomorrow.be', $password],
        ]);

        $admins->each(function($admin){
            $this->createUser($admin[0], $admin[1], $admin[2], $admin[3]);
        });

        $this->info('Added devteam as admins. All with your provided password.');
    }

}
