<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        //$panger = auth('admin')->user()->role->permission;
        //return $panger['admins'];
        //return in_array('admins_create', $panger['admins']) ? 1 : 0;

        return view('dashboard.index');
    }
}
