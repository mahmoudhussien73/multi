<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{

    public function index(){

        //$this->authorize('roles_view');
        $roles = Role::get();
        return view('dashboard.roles.index', compact('roles'));
    }

    public function create(){

        $this->authorize('roles_crteate');
        return view('dashboard.roles.create');

    }

    public function store(Request $request){

        //$this->authorize('roles_create');
        $this->validate($request, [
            'name'   => 'required|unique:roles,name',
        ]);

        Role::insert([
            'name'  => $request->input('name')
        ]);

        return redirect()->route('dashboard.roles')->with(['success' => 'تم ألاضافة بنجاح']);

    }

    public function edit($id){

        //$this->authorize('roles_update');
        $role = Role::find($id);

        if(!$role){
            return redirect()->route('dashboard.roles')->with(['error' => 'غير موجود']);
        }

        return view('dashboard.roles.edit', compact('role'));
    }

    public function update($id, Request $request){

        //$this->authorize('roles_update');
        $this->validate($request, [
            'name'   => 'required',
        ]);

        $role = Role::find($id);

        if(!$role){
            return redirect()->route('dashboard.roles')->with(['error' => 'غير موجود']);
        }

        $role->update([
            'name'  => $request->name
        ]);

        return redirect()->route('dashboard.roles')->with(['success' => 'تم التعديل بنجاح']);

    }

    public function delete($id){

        //$this->authorize('roles_delete');
        $role = Role::find($id);

        if(!$role){
            return redirect()->route('dashboard.roles')->with(['error' => 'غير موجود']);
        }

        $role->delete();
        return redirect()->route('dashboard.roles')->with(['success' => 'تم الحذف بنجاح']);

    }
}
