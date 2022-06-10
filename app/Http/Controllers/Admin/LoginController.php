<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{



    public function login(){
        return view('dashboard.login');
    }

    public function dashlogin(Request $request){
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        $remember_me = $request->has('remember_me') ? true : false;

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password],$remember_me)) {

            return redirect()->intended('dashboard/');
        }
        return back()->withInput($request->only('email'));
    }


    public function logout(){

        $gaurd = $this->getGaurd();
        $gaurd->logout();

        return redirect()->route('dashboard.login');
    }

    private function getGaurd(){
        return auth('admin');
    }
}
