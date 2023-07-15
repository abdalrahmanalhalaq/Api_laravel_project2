<?php

namespace App\Http\Controllers;

// use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //


        $data = Role::all();
        return new Response(['status'=>true , 'data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        //
        $validator = Validator($request->all() , [
            'name'=>'required|string|max:30',
            'guard_name'=>'required|string|in:admins-api',
        ]);
        if(! $validator->fails()){
            $role = Role::create($request->all( ));
            return new Response(['status'=>true , 'dataRole'=>$role ,  'add Role Successfully'] ,Response::HTTP_OK);

        }else{
            return new Response(['status'=>false] , Response::HTTP_BAD_REQUEST);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
    public function UpdateRolesPermission(Request $request , Role $role , Permission $permission)
    {
        // dd($permission);

        if($role->guard_name == $permission->guard_name){
            if($role->hasPermissionTo($permission)){
                $role->revokePermissionTo($permission);
                return new Response(['status'=>true , 'message'=>'Revoked Permission Successfully'] ,Response::HTTP_OK);

            }else{
                $role->givePermissionTo($permission);
                return new Response(['status'=>true , 'message'=>'giving Permission Successfully'] ,Response::HTTP_OK);
            }
        }else{
            return new Response(['status'=>false, 'message'=>'role & permission not same guard'] , Response::HTTP_BAD_REQUEST);

        }

    }
}
