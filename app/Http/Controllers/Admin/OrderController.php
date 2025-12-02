<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){

        return view('adminlayout.orders.orders');
    }
    public function indexdetail($id)
    {
        return view('adminlayout.orders.details', compact('id'));
    }


        
}
