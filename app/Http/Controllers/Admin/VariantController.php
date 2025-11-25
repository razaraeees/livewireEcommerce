<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function index(){

        return view('adminlayout.variant.index');
    }

    public function create(){

        return view('adminlayout.variant.create');
    }

    public function edit($slug){

        return view('adminlayout.variant.edit', compact('slug'));
    }
}
