<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Authorization\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Admin\Authorization\Permission;
use Thinktomorrow\Chief\Admin\Authorization\Role;

class GenerateRoleCommand extends Command
{
    protected $signature = 'chief:role
                                {name : the role name e.g. admin, editor.}
                                {--permissions= : the permissions to give to this role. Assign multiple permissions by comma separated values. }';

    protected $description = 'Generate a new role';

    public function handle(): void
    {
        $roleName = $this->getNameArgument();

        $role = Role::findOrCreate($roleName, 'chief');

        $this->assignPermissionsToRole($role);
    }

    private function getNameArgument(): string
    {
        return strtolower(Str::singular($this->argument('name')));
    }

    /**
     * @return void
     */
    private function assignPermissionsToRole(Role $role)
    {
        if (! $this->option('permissions')) {
            return;
        }

        $permissionNames = explode(',', $this->option('permissions'));

        $cleanPermissionNames = [];
        foreach ($permissionNames as $permissionName) {
            $permissionName = trim($permissionName);
            // Generate all permissions if only scope is passed
            if (strpos($permissionName, '-') === false) {
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

        $this->info('Role '.$role->name.' was assigned the permissions: '.implode(',', $cleanPermissionNames));
    }
}
