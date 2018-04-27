<?php

namespace Chief\Authorization;

use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    protected $guard_name = 'admin';

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? 'admin';

        return parent::create($attributes);
    }

    public static function generate($scope): array
    {
        $abilities = ['view', 'create', 'update', 'delete'];

        return array_map(function($val) use ($scope) {
            return $val . '-'. $scope;
        }, $abilities);
    }

    public static function getPermissionsForIndex()
    {
        $permissions = $temp = [];
        self::all()->each(function($permission) use(&$permissions, &$temp){
            $model = explode("_", $permission->name, 2)[1];
            $temp[$model][$permission->id] = explode("_", $permission->name, 2)[0];
            $permissions = $temp;
        });
        return $permissions;
    }

}
