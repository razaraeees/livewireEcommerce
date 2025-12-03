<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function index()
    {
        return view('adminlayout.seo.index');
    }
    public function create()
    {
        return view('adminlayout.seo.create');
    }
    public function edit($id)
    {
        return view('adminlayout.seo.edit', ['id' => $id]);
    }
}
