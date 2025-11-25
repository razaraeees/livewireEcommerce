<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(){

        return view('adminlayout.brands.index');
    }

    public function create(){

        return view('adminlayout.brands.create');
    }
    public function edit($slug)
    {

        return view('adminlayout.brands.edit', compact('slug'));
    }
}
