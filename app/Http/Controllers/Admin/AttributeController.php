<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index(){

        return view('adminlayout.attributes.index');
    }

    public function  create(){

        return view('adminlayout.attributes.create');
    }

    public function  edit($slug){

        return view('adminlayout.attributes.edit', compact('slug'));
    }
}
