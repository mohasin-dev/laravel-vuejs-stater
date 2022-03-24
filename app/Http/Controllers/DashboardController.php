<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public static function index()
    {
        $user = auth()->user()->load(['roles.permissions' => function ($query) {
            $query->select('id', 'permission', 'module');
            $query->where('permission', 'Read');
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