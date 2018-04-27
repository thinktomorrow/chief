<?php
namespace Chief\Authorization\Console;

use Chief\Authorization\Role;
use Chief\Authorization\Permission;
use Illuminate\Console\Command;

class GeneratePermissionCommand extends Command
{
    protected $signature = 'chief:permission 
                                {name : the permission scope e.g. post, user.}
                                {--role= : the role that is given these permissions. Assign multiple roles by comma separated values. }';

    protected $description = 'Generate default permissions';

    public function handle()
    {
        $permissions = Permission::generate($this->getNameArgument());

        $this->createPermissions($permissions);
        $this->assignPermissionsToRoles($permissions);
    }

    private function getNameArgument()
    {
        return str_slug(
            strtolower(str_singular($this->argument('name')))
        );
    }


    /**
     * @param $permissions
     */
    private function createPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        $this->info('Permissions ' . implode(', ', $permissions) . ' created.');
    }

    /**
     * @param $permissions
     */
    private function assignPermissionsToRoles($permissions)
    {
        if(!$this->option('role')) return;

        $roleNames = explode(',', $this->option('role'));

        foreach ($roleNames as $roleName) {
            if ($role = Role::where('name', trim($roleName))->first()) {
                $role->syncPermissions($permissions);
                $this->info('Role ' . $roleName . ' assigned the given permissions.');
            } else {
                $this->warn('Role not found by name ' . $roleName . '!');
            }
        }
    }
}