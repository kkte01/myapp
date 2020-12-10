<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
class UsersController extends Controller
{
    //사용자 등록 폼으로 이동
    public function create(){

        return view('users.create');

    }
    //사용자 등록 요청 처리
    public function store(Request $request){
        //소셜 사용자는 이메일은 있지만 비밀번호가 없는 회원이기에 조건을 이용 찾는다.
        $socialUser = User::whereEmail($request->input('email'))->whereNull('password')->first();

        if($socialUser){
            return $this->updateSocialAccount($request, $socialUser);
        }
        //원래 store내용을 이 함수로 옮김
        return $this->createNativeAccount($request);
    }
    //소셜로그인회원으로 사이트 사용자 만들기
    protected function updateSocialAccount(Request $request, User $user){
        $this->validate($request, [
            'name'=> 'required|max:50',
            'email'=> 'required|email|max:100',
            'password' => 'required|confirmed|min:6',
        ]);

        $user->update([
            'name' => $request->input('name'),
            'password' => bcrypt($request->input('password')),
        ]);
        auth()->login($user);
        return redirect('/');
    }

    //사용자 메일 확인\
    public function  confirm($code){
        $user = User::whereConfirmCode($code)->first();
        if(!$user){
            
            return redirect('/');
        }

        $user->activated = 1;
        
        $user->confirm_code = null;

        $user->save();

        auth()->login($user);

        return redirect('home');
    }

    //이미 로그인한 사용자가 회원가입 주소를 직접 입력하는것 막기
    public function __construct(){

        //$this->middleware('guest');

    }
        public function createNativeAccount(Request $request){
            $message =[
                'name.required' => '이름이 2글자 미만이거나 50자 이상입니다.',
                'email.required' => '이메일이 6자미만이거나 50자이상입니다.',
                'password.required' => '비밀번호는 최소 6자이상입니다.'
            ];
            $this->validate($request, [
                'name' => 'required|max:50|min:2',
                'email' => 'required|email|max:50|min:6|unique:users',
                'password' => 'required|confirmed|min:6',
                'password_confirmation' =>'required'
            ], $message);
    
            $confirmCode = Str::random(60);
    
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'confirm_code' => $confirmCode
            ]);
            //컨트롤러가 아닌 이벤트를 설정해 보내기
            // \Mail::send('emails.auth.confirm', compact('user'), function($message) use ($user){
            //     $message->to($user->email);
            //     $message->subject(
            //         sprintf('[%s] 회원가입을 확인해 주세요', config('app.name'))
            //     );
            // });
            event(new \App\Events\UserCreated($user));
            
            auth()->login($user);
    
            return redirect()->route('/');
        }
    
}
