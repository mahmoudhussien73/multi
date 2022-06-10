<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Role;
use Illuminate\Http\Request;
use App\Models\Admin\Permission;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index(){

        //$this->authorize('permissions_view');
        $permissions = Permission::get();
        //return $permissions;
        return view('dashboard.permissions.index', compact('permissions'));
    }

    public function create(){

        //$this->authorize('permissions_create');
        $roles = Role::get();
        return view('dashboard.permissions.create', compact('roles'));

    }

    public function store(Request $request){

        //return $request->all();

       //$this->authorize('permissions_create');
        $this->validate($request, [
            'role_id'   => 'required|unique:permissions,role_id',
            'permissions'   => 'required',
        ]);

        Permission::create([
            'role_id'  => $request->role_id,
            'permissions'  => $request->permissions
        ]);

        return redirect()->route('dashboard.permissions')->with(['success' => 'تم ألاضافة بنجاح']);

    }

    public function edit($id){

        //$this->authorize('permissions_update');

        $permission = Permission::find($id);

        //return $permission->permissions['users'];


        if(!$permission){
            return redirect()->route('dashboard.permissions')->with(['error' => 'غير موجود']);
        }

        $roles = Role::get();

        return view('dashboard.permissions.edit', compact('permission', 'roles'));
    }

    public function update($id, Request $request){

        // $this->validate($request, [
        //     'role_id'  => $request->role_id,
        //     'permissions'  => $request->permissions
        // ]);

        //$this->authorize('permissions_update');

        $role = Permission::find($id);

        if(!$role){
            return redirect()->route('dashboard.permissions')->with(['error' => 'غير موجود']);
        }

        $role->update([
            'role_id'  => $request->role_id,
            'permissions'  => $request->permissions
        ]);

        return redirect()->route('dashboard.permissions')->with(['success' => 'تم التعديل بنجاح']);

    }

    public function delete($id){

        //$this->authorize('permissions_delete');
        $role = Permission::find($id);

        if(!$role){
            return redirect()->route('dashboard.permissions')->with(['error' => 'غير موجود']);
        }

        $role->delete();
        return redirect()->route('dashboard.permissions')->with(['success' => 'تم الحذف بنجاح']);

    }
}
