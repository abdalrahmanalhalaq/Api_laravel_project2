<?php

namespace App\Http\Controllers;

// use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Permission::all();
        return new Response(['status'=>true , 'data'=>$data]);


        // $data = Permission::all();
        // return new Response(['status'=>true , 'data'=>$data]);
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
                        $permission = Permission::create($request->all());
                        return new Response(['status'=>true , 'dataRole'=>$permission ,  'add Permission Successfully'] ,Response::HTTP_OK);

                    }else{
                        return new Response(['status'=>false] , Response::HTTP_BAD_REQUEST);
                    }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        //
    }
}
