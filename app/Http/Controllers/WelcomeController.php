<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WelcomeController extends Controller
{
    //
    public function index(){
        return view('welcome');
    }

    public function locale(Request $request, $locale) {
        Session::put('locale',$locale);

        return back()->withInput();
    }

}
