<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(){

        return view('adminlayout.banner.banner');
    }

    public function create(){

        return view('adminlayout.banner.create');
    }
    public function edit($id)
    {

        // dd([
        //     'step' => 'Controller',
        //     'id' => $id,
        //     'type' => gettype($id),
        //     'banner_exists' => Banner::where('id', $id)->exists()
        // ]);
        return view('adminlayout.banner.edit', compact('id'));
    }

}
