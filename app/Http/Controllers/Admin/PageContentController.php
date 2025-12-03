<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class PageContentController extends Controller
{
    public function index(){

        return view('adminlayout.page-content.index');
    }

    public function create(){

        return view('adminlayout.page-content.create');
    }

    public function edit($slug){

        return view('adminlayout.page-content.edit', ['slug' => $slug]);
    }
}
