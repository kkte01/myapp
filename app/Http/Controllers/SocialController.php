<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
class SocialController extends Controller
{
    //
    // public function __construct(){
    //     $this->middleware('guest');
    // }
    public function execute(Request $request, $provider){
        //dd($request);
        if(!$request->has('code')){
            //code가 없기에 여기로 들어오는거 확인
            //dd($provider);
            return $this->redirectToProvider($provider);
            //return view('sessions.fail2');
        }

        return $this->handleProviderCallback($provider);
        //return view('sessions.loginfail');
    }

    //깃허브로 향하는 리디렉션 응답을 반환한다.
    protected function redirectToProvider($provider){
        //dd($provider);
        return Socialite::driver($provider)->redirect();
        //return view('sessions.loginfail');
        
    }

    //쿼리 스트링에 code필드가 있을 때 동작하는 메서드
    protected function handleProviderCallback($provider){

        $user = Socialite::driver($provider)->user();
        
        //dd($user);
        $user = (\App\Models\User::whereEmail($user->getEmail())->first()) ?: \App\Models\User::create([
            'name' => $user->getName() ? : 'unknown',
            'email' => $user->getEmail(),
            'activated' => 1,
        ]);
        auth()->login($user);

        return redirect('home');
    }
}
