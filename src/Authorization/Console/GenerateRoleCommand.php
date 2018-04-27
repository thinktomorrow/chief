<?php
namespace Chief\Authorization\Console;

use Chief\Authorization\Role;
use Chief\Authorization\Permission;
use Illuminate\Console\Command;

class GenerateRoleCommand extends Command
{
    protected $signature = 'chief:role 
                                {name : the role name e.g. admin, editor.}
                                {--permissions= : the permissions to give to this role. Assign multiple permissions by comma separated values. }';

    protected $description = 'Generate a new role';

    public function handle()
    {
        $roleName = $this->getNameArgument();

        $role = Role::create(['name' => $roleName]);

        $this->assignPermissionsToRole($role);
    }

    private function getNameArgument()
    {
        return strtolower(str_singular($this->argument('name')));
    }

    private function assignPermissionsToRole(Role $role)
    {
        if(!$this->option('permissions')) return;

        $permissionNames = explode(',', $this->option('permissions'));

        foreach($permissionNames as $k => $permissionName){

            // Trim the value
            $permissionNames[$k] = trim($permissionName);

            // Generate all permissions if only scope is passed
            if(false === strpos($permissionName, '-')){
                unset($permissionNames[$k]);
                $permissionNames = array_merge($permissionNames, Permission::generate($permissionName));
            }
        }

        $role->givePermissionTo($permissionNames);

        $this->info('Role ' . $role->name . ' was assigned the permissions: ' . implode(',', $permissionNames));
    }
}