<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QualityController extends Controller
{
    public function index(){
    	return view('quality_en');
    }

    public function id(){
    	return view('quality_id');
    }
}
