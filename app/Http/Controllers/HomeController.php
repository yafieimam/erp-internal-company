<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
    	return view('home');
    }

    public function en(){
    	return view('en');
    }

    public function id(){
    	return view('id');
    }

    public function offline(){
    	return view('vendor/laravelpwa/offline');
    }
}
