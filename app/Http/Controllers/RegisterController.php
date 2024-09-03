<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function en(){
    	if(!Session::get('login')){
    		if(!Session::get('login_admin')){
    			return view('register_en');
    		}else{
    			return redirect('/homepage')->with('alert','You Are Already Login');
    		}
    		return view('register_en');
        }else{
        	return redirect('/')->with('alert','You Are Already Login');
        }
    }

    public function id(){
    	if(!Session::get('login')){
    		if(!Session::get('login_admin')){
    			return view('register_id');
    		}else{
    			return redirect('/homepage')->with('alert','You Are Already Login');
    		}
    		return view('register_id');
        }else{
        	return redirect('/')->with('alert','Anda Sudah Login');
        }
    }
}
