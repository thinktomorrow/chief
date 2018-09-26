<?php
namespace Thinktomorrow\Chief\Authorization\Console;

use Thinktomorrow\Chief\Authorization\Role;
use Thinktomorrow\Chief\Authorization\Permission;
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

        $role = Role::findOrCreate($roleName, 'chief');

        $this->assignPermissionsToRole($role);
    }

    private function getNameArgument()
    {
        return strtolower(str_singular($this->argument('name')));
    }

    private function assignPermissionsToRole(Role $role)
    {
        if (!$this->option('permissions')) {
            return;
        }

        $permissionNames = explode(',', $this->option('permissions'));

        $cleanPermissionNames = [];
        foreach ($permissionNames as $k => $permissionName) {
            $permissionName = trim($permissionName);
            // Generate all permissions if only scope is passed
            if (false === strpos($permissionName, '-')) {
                $cleanPermissionNames = array_merge($cleanPermissionNames, Permission::generate($permissionName));
            } else {
                // Trim the value
                $cleanPermissionNames[] = $permissionName;
            }
        }

        foreach ($cleanPermissionNames as $cleanPermissionName) {
            if ($role->hasPermissionTo($cleanPermissionName)) {
                continue;
            }
            $role->givePermissionTo($cleanPermissionName);
        }

        $this->info('Role ' . $role->name . ' was assigned the permissions: ' . implode(',', $cleanPermissionNames));
    }
}
