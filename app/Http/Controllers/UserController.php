<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helpers\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Http\Requests\CreateUserRequest;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }
    
    public function index(Request $request)
    {
        $users = User::query();

        if (! empty($request->search)) {
            $users->where('name', 'LIKE', "%$request->search%");
        }

        $users = $users->orderBy('name')->paginate(10);
       
        $data['permissions'] = Helpers::getPermission('User');

        return UserResource::collection($users)->additional($data);
    }

    public function create()
    {
        return RoleResource::collection(Role::all());
    }

    public function store(CreateUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            $user->assignRole($request->roles);
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(CreateUserRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            $user->syncRoles($request->roles);

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function edit(User $user)
    {
       $user->load('roles');

       return UserResource::make($user);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user = User::whereId($id)->first();

            $user->roles()->detach();

            $user->delete();

            DB::commit();
            
            return response()->json(['message' => 'Deleted successful', 'code' => 200], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
