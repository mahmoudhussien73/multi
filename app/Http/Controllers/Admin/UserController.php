<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class UserController extends Controller
{

    public function index(){

        //$this->authorize('roles_view');
        $users = User::user()->get();
        return view('dashboard.users.index', compact('users'));
    }

    public function create(){

        $this->authorize('users_crteate');
        return view('dashboard.users.create');

    }

    public function store(Request $request){

        //$this->authorize('users_create');
        $this->validate($request, [
            'name'   => 'required|unique:users,name',
        ]);

        User::insert([
            'name'  => $request->input('name')
        ]);

        return redirect()->route('dashboard.users')->with(['success' => 'تم ألاضافة بنجاح']);

    }

    public function edit($id){

        //$this->authorize('users_update');
        $user = User::find($id);

        if(!$user){
            return redirect()->route('dashboard.users')->with(['error' => 'غير موجود']);
        }

        return view('dashboard.users.edit', compact('user'));
    }

    public function update($id, Request $request){

        try{

            //return $request;
            //$this->authorize('users_update');
            $this->validate($request, [
                'full_name' => 'required',
                'username'  => 'nullable',
                'email'     => 'required'
            ]);

            $user = User::find($id);

            if(!$user){
                return redirect()->route('dashboard.users')->with(['error' => 'غير موجود']);
            }

            $file_path = $user->photo;

            if(isset($request->photo)){
                if($user->photo != ''){
                    if(is_file( base_path('public/assets/admin/' . $user->photo) )){
                        unlink( base_path('public/assets/admin/' . $user->photo) );
                    }
                }

                $file_path = uploadImage('users', $request->photo);
            }

            $password = $user->password;

            if(isset($request->password) && $request->password != ''){

                if(strlen($request->password) < 8){
                    return redirect()->back()->with(['error' => 'password length is less than 8 char']);
                }

                if($request->password !== $request->password_confirmation){
                    return redirect()->back()->with(['error' => 'password confirmation not equal']);
                }

                $password = bcrypt($request->password);
            }

            $user->update([
                'full_name' => $request->full_name,
                'username'  => $request->username,
                'email'     => $request->email,
                'address'     => $request->address ?? '',
                'status'     => $request->status ?? 0,
                'password'  => $password,
                'phone'  => $request->phone ?? $user->phone,
                'photo'     => $file_path
            ]);

            return redirect()->route('dashboard.users')->with(['success' => 'تم التعديل بنجاح']);

        }catch(\Exception $ex){
            return $ex;
            return redirect()->route('dashboard.users')->with(['error' => 'برجاء المحاولة في وقت لاحق']);
        }

    }

    public function delete($id){

        try{

            //$this->authorize('users_delete');
            $user = User::find($id);

            if(!$user){
                return redirect()->route('dashboard.users')->with(['error' => 'غير موجود']);
            }

            if($user->photo != ''){
                if(is_file( base_path('public/assets/admin/' . $user->photo) )){
                    unlink( base_path('public/assets/admin/' . $user->photo) );
                }
            }

            $user->delete();
            return redirect()->route('dashboard.users')->with(['success' => 'تم الحذف بنجاح']);

        }catch(\Exception $ex){
            return $ex;
            return redirect()->route('dashboard.users')->with(['error' => 'برجاء المحاولة في وقت لاحق']);
        }

    }
}
