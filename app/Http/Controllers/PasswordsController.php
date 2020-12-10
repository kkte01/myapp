<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
class PasswordsController extends Controller
{
    //
    public function __construct(){
        //$this->middleware('guest');
    }
    //비밀번호 변경창으로 보내주기
    public function getRemind(){
        return view('passwords.remind');
    }

    //비밀번호 변경처리
    public function postRemind(Request $request){
        $this->validate($request, ['email' => 'required|email|exists:users']);

        $email = $request->get('email');
        $token = Str::random(64);
        \DB::table('password_resets')-> insert([
            'email' => $email,
            'token' => $token,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        //이벤트로 처리 하기
        // \Mail::send('emails.passwrods.reset', compact('token'), function($message) use ($email){
        //     $message->to($email);
        //     $message->subject(
        //         sprintf('[%s] 비밀번호를 초기화하세요', config('app.name'))
        //     );
        // });
        event(new \App\Events\PasswordRemindCreated($email, $token));

        return redirect('home');
    }
    public function getReset($token = null){
        return view('passwords.reset', compact('token'));
    }
    public function postReset(Request $request){
        $this->validate($request, [
            'email'=> 'required|email|exists:users',
            'password' => 'required|confirmed',
            'token'=> 'required'
        ]);

        $token = $request->get('token');

        if(! \DB::table('password_resets')->whereToken($token)-> first()){

            return back()->withInput();
        }
        //비밀번호 변경
        \App\Models\User::whereEmail($request->input('email'))->first()->update([
            'password' => bcrypt($request->input('password'))
        ]);
        //토큰 삭제
        \DB::table('password_resets')->whereToken($token)->delete();

        return redirect('home');
    }
}
