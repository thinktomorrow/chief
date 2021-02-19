<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Authorization\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Admin\Authorization\Permission;
use Thinktomorrow\Chief\Admin\Authorization\Role;

class GeneratePermissionCommand extends Command
{
    protected $signature = 'chief:permission
                                {name : the permission scope e.g. post, user.}
                                {--role= : the role that is given these permissions. Assign multiple roles by comma separated values. }';

    protected $description = 'Generate default permissions';

    public function handle()
    {
        $scope = $this->getNameArgument();
        $permissions = (false === strpos($scope, '-')) ? Permission::generate($scope) : [$scope];

        $this->createPermissions($permissions);
        $this->assignPermissionsToRoles($permissions);
    }

    private function getNameArgument()
    {
        return Str::slug(
            strtolower(Str::singular($this->argument('name')))
        );
    }


    /**
     * @param $permissions
     */
    private function createPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'chief');
        }

        $this->info('Permissions ' . implode(', ', $permissions) . ' created.');
    }

    /**
     * @param $permissions
     */
    private function assignPermissionsToRoles($permissions)
    {
        if (! $this->option('role')) {
            return;
        }

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
