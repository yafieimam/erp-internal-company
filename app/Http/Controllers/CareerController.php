<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index(){
    	return view('career_en');
    }

    public function id(){
    	return view('career_id');
    }
}
