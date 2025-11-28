<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(){

        return view('adminlayout.coupon.index');
    }
    public function create(){

        return view('adminlayout.coupon.create');
    }

    public function edit($id)
    {
        return view('adminlayout.coupon.edit', compact('id'));
    }
}
