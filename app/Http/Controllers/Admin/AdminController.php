<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;

class AdminController extends Controller
{

    public function index(){

        //$this->authorize('roles_view');
        $admins = Admin::get();
        return view('dashboard.admins.index', compact('admins'));
    }

    public function create(){

        //$this->authorize('roles_crteate');
        return view('dashboard.admins.create');

    }

    public function store(Request $request){

        //$this->authorize('roles_create');
        $this->validate($request, [
            'name'   => 'required|unique:admins,name',
        ]);

        Admin::insert([
            'name'  => $request->input('name')
        ]);

        return redirect()->route('dashboard.admins')->with(['success' => 'تم ألاضافة بنجاح']);

    }

    public function edit($id){

        //$this->authorize('roles_update');
        $admin = Admin::find($id);

        if(!$admin){
            return redirect()->route('dashboard.admins')->with(['error' => 'غير موجود']);
        }

        return view('dashboard.admins.edit', compact('admin'));
    }

    public function update($id, Request $request){

        //$this->authorize('roles_update');
        $this->validate($request, [
            'name'   => 'required',
        ]);

        $admin = Admin::find($id);

        if(!$admin){
            return redirect()->route('dashboard.admins')->with(['error' => 'غير موجود']);
        }

        $admin->update([
            'name'  => $request->name
        ]);

        return redirect()->route('dashboard.admins')->with(['success' => 'تم التعديل بنجاح']);

    }

    public function delete($id){

        //$this->authorize('roles_delete');
        $admin = Admin::find($id);

        if(!$admin){
            return redirect()->route('dashboard.admins')->with(['error' => 'غير موجود']);
        }

        $admin->delete();
        return redirect()->route('dashboard.admins')->with(['success' => 'تم الحذف بنجاح']);

    }
}
