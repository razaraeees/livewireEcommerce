<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnnoucementController extends Controller
{
    public function index(){

        return view('adminlayout.annoucement.annouce');
    }

    public function create(){

        return view('adminlayout.annoucement.create');
    }

    public function edit($id){

        // dd ($id);
        return view('adminlayout.annoucement.edit', ['id' => $id]);
    }
}
