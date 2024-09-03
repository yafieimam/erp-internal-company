<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(){
    	return view('contact_en');
    }

    public function id(){
    	return view('contact_id');
    }
}
