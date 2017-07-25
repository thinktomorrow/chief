<?php

namespace App;

class Permission extends \Spatie\Permission\Models\Permission
{

    public static function defaultPermissions()
    {
        return [
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',

            'view_roles',
            'add_roles',
            'edit_roles',
            'delete_roles',

            'view_permissions',
            'add_permissions',
            'edit_permissions',
            'delete_permissions',
        ];
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
