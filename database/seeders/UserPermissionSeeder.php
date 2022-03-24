<?php

namespace Database\Seeders;

use App\Enums\AclEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UserPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $read = Permission::create(['name' => 'users.index', 'module' => 'User', 'permission' => AclEnum::Read]);
        Permission::create(['name' => 'users.search', 'module' => 'User', 'parent_permission' => $read->id]);

        Permission::create(['name' => 'users.store', 'module' => 'User', 'permission' => AclEnum::Create]);
        Permission::create(['name' => 'users.update', 'module' => 'User', 'permission' => AclEnum::Update]);
        Permission::create(['name' => 'users.destroy', 'module' => 'User', 'permission' => AclEnum::Delete]);

    }
}
