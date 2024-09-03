<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(){
    	return view('products_en');
    }

    public function id(){
    	return view('products_id');
    }
}
