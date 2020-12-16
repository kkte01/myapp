<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class SessionsController extends Controller
{
    //
    public function __construct(){

        //$this->middleware('guest', ['except'=> 'destroy']);

    }

    public function create(){
        return view('sessions.create');
    }

    public function store(Request $request){

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        //존재하지 않은 email과 password 일 경우
        //attempt 함수 bool값을 반환

        if(!Auth::attempt($request->only('email', 'password'))){

            //return view('sessions.loginfail');
            return back()->withInput();
        }
        //이메일 인증을 안했을 경우
        if(!auth()->user()->activated){

            auth()->logout();

            return back()->withInput();
            //return view('sessions.fail2');
        }

        return redirect('home');
    }
    public function destroy(){
        auth()->logout();

        return redirect('home');
    }

    /* Response Methods */

    /**
     * Make a success response.
     *
     * @param string|boolean $message
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondCreated($message)
    {
        return ($return = request('return'))
            ? redirect(urldecode($return))
            : redirect()->intended(route('home'));
    }
}
