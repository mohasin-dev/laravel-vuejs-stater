<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\SimpleCrud;
use Illuminate\Http\Request;
use App\Http\Resources\SimpleCrudResource;

class SimpleCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = SimpleCrud::query();

        if (! empty($request->search)) {
            $roles->where('name', 'LIKE', "%$request->search%");
        }

        $roles = $roles->orderBy('name')->paginate(10);

        $data['permissions'] = Helpers::getPermission('SimpleCrud');

        return SimpleCrudResource::collection($roles)->additional($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'email' => 'required|email|unique:simple_cruds',
            'mobile' => 'required|unique:simple_cruds'
        ]);
        
        $simpleCrud = new SimpleCrud();
        $simpleCrud->name = $request->name;
        $simpleCrud->email = $request->email;
        $simpleCrud->mobile = $request->mobile;
        $simpleCrud->save();

        return response()->json(['message' => 'Data successful created', 'code' => 200], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SimpleCrud  $simpleCrud
     * @return \Illuminate\Http\Response
     */
    public function show(SimpleCrud $simpleCrud)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SimpleCrud  $simpleCrud
     * @return \Illuminate\Http\Response
     */
    public function edit(SimpleCrud $simpleCrud)
    {
        return SimpleCrudResource::make($simpleCrud);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SimpleCrud  $simpleCrud
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SimpleCrud $simpleCrud)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:simple_cruds,email,' . $simpleCrud->id,
            'mobile' => 'required|unique:simple_cruds,mobile,' . $simpleCrud->id
        ]);

        $simpleCrud->name = $request->name;
        $simpleCrud->email = $request->email;
        $simpleCrud->mobile = $request->mobile;
        $simpleCrud->save();

        return response()->json(['message' => 'Data successful updated', 'code' => 200], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SimpleCrud  $simpleCrud
     * @return \Illuminate\Http\Response
     */
    public function destroy(SimpleCrud $simpleCrud)
    {
        $simpleCrud->delete();

        return response()->json(['message' => 'Data successful deleted', 'code' => 200], 200);
    }
}
