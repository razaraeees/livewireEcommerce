<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(){

        return view('adminlayout.categories.index');
    }

    public function create(){

        return view('adminlayout.categories.create');
    }
    public function edit($url)
    {

        return view('adminlayout.categories.edit', compact('url'));
    }
}
