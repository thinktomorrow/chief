<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Authorization;

use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    protected $guard_name = 'chief';

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? 'chief';

        parent::__construct($attributes);
    }

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? 'chief';

        return parent::create($attributes);
    }

    public static function generate(string $scope): array
    {
        $abilities = ['view', 'create', 'update', 'delete'];

        return array_map(function ($val) use ($scope) {
            return $val.'-'.$scope;
        }, $abilities);
    }

    public static function getPermissionsForIndex()
    {
        $permissions = $temp = [];
        self::all()->each(function ($permission) use (&$permissions, &$temp) {
            $model = explode('_', $permission->name, 2)[1];
            $temp[$model][$permission->id] = explode('_', $permission->name, 2)[0];
            $permissions = $temp;
        });

        return $permissions;
    }
}
