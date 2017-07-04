<?php

namespace App;

class Role extends \Spatie\Permission\Models\Role
{

    public function getPermissionsForIndex()
    {
        $this->permissions->each(function($permission){

            $model = explode("_", $permission->name, 2)[1];
            $temp = $this->permission;
            $temp[$model][] = explode("_", $permission->name, 2)[0];

            $this->permission = $temp;
        });
        return $this->permission;
    }
}
