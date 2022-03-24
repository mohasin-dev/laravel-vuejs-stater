<?php

namespace App\Helpers;

class Helpers
{
    public static function getPermission($module)
    {
        $user = auth()->user()->load(['roles.permissions' => function ($query) use ($module) {
            $query->select('id', 'permission', 'module');
            $query->where('module', $module);
        }]);

        $permissions = $user->roles->flatMap(function ($role) {
            return $role->permissions;
        })->sort()->values();

        return $permissions->map(function ($value) {
            return [
            'permission' => $value->permission,
            'module'     => $value->module,
        ];
        });
    }
}
