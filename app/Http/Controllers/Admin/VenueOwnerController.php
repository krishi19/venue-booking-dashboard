<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VenueOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $owners = Admin::
            where('role','Venue Owner')
        ->when($request->search, function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
            $q->orWhere('email', 'like', '%' . $request->search . '%');
            $q->orWhere('mobile', 'like', '%' . $request->search . '%');
            $q->orWhere('address', 'like', '%' . $request->search . '%');
        })->orderBy('updated_at','Desc')->get();
        return response()->json(['data'=>$owners]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:190',
            'email'=>'required|string|max:190|unique:admins',
            'password'=>'required|string|max:190',
        ]);
        $admins= Admin::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'mobile'=>$request->mobile,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'password'=>Hash::make($request->password),
            'role'=>'Venue Owner'
        ]);
        return response()->json(['data'=>$admins]);
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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Admin $admin
     * @return void
     */
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name'=>'required|string|max:190',
            'email'=>'required|string|max:190|unique:admins,email,'.$admin->id,
            'password'=>'required|string|max:190',
        ]);
        $admin= tap($admin)->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'mobile'=>$request->mobile,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'password'=>Hash::make($request->password),
        ]);
        return response()->json(['data'=>$admin]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Admin $admins
     * @return void
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return response()->json(['message'=>'Deleted Successfully']);

    }
}
