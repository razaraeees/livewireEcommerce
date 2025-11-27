<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){

        
        return view('adminlayout.products.index');
    }

    public function create(){

        
        return view('adminlayout.products.create');
    }
    public function edit($slug){

        
        return view('adminlayout.products.edit', compact('slug'));
    }
    
}
