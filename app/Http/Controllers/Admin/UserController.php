<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){

        return view('adminlayout.user.index');
    }

    public function subIndex(){

        return view('adminlayout.user.sub_index');
    }

    public function inquiriesIndex(){

        return view('adminlayout.user.inquiries_index');
    }
}
