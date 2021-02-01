<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Authorization;

use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole implements RoleContract
{
    protected $guard_name = 'chief';

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? 'chief';

        parent::__construct($attributes);
    }

    public static function rolesForSelect($includeDeveloperRole = false)
    {
        $roles = $includeDeveloperRole ? static::all() : static::all()->reject(function ($role) {
            return $role->name == 'developer';
        });

        return $roles->pluck('name')->toArray();
    }

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? 'chief';

        return parent::create($attributes);
    }

    public function permissionNames()
    {
        return $this->permissions->pluck('name')->toArray();
    }

    public function getPermissionsForIndex()
    {
        $this->permissions->each(function ($permission) {
            $model = explode("_", $permission->name, 2)[1];
            $temp = $this->permission;
            $temp[$model][] = explode("_", $permission->name, 2)[0];

            $this->permission = $temp;
        });

        return $this->permission;
    }
}
