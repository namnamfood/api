<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\JSON;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function index()
    {

    }

    public function show()
    {

    }

    public function store(Request $request)
    {
        $admin = Admin::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $admin
            ->roles()
            ->attach(Role::find($request->role_id));
        return JSON::response(false, 'Success!! New admin added!', $admin, 200);

    }

    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);
        $role = Role::find($request->get('role_id'));

        $admin->roles()->attach($role);


        return JSON::response(false, 'Success!! Admin updated!', $admin, 200);

    }

    public function destroy()
    {

    }
}
