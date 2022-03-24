<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::query();

        if (! empty($request->search)) {
            $roles->where('name', 'LIKE', "%$request->search%");
        }

        $roles = $roles->orderBy('name')->paginate(10);

        $data['permissions'] = Helpers::getPermission('Role');

        return RoleResource::collection($roles)->additional($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $permissions = Permission::where('parent_permission', 0)->get();

        return PermissionResource::collection($permissions)->groupBy('module');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'permissions' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $role = new Role;
            $role->name = $request->name;
            $role->save();

            $childPermissions = Permission::whereIn('parent_permission', $request->permissions)->pluck('id')->toArray();

            $permissions = array_merge($request->permissions, $childPermissions);

            $role->givePermissionTo($permissions);
            
            DB::commit();

            return response()->json(['message' => 'Role successfully created', 'code' => 200], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $data['permissions'] = $role->getAllPermissions()->pluck('id');

        return RoleResource::make($role)->additional($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required',
            'permissions' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $role->name = $request->name;
            $role->save();

            $childPermissions = Permission::whereIn('parent_permission', $request->permissions)->pluck('id')->toArray();

            $permissions = array_merge($request->permissions, $childPermissions);

            $role->syncPermissions($permissions);
            
           DB::commit();

            return response()->json(['message' => 'Role successfully updated', 'code' => 200], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        return $role;
        try {
            DB::beginTransaction();

            $role->permissions()->detach();
            $role->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Role successfully deleted']);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
