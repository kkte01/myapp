<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller{
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }
    /************************************************************************
     * @description : 로그인
     * @url         : /login
     * @method      : POST
     * @return      : json
     ************************************************************************/
    public function store(Request $request){
        $request->validate([
            'email' => 'required|string' ,
            'password' => 'required|string' ,
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email' , 'password']);
        if (!Auth::attempt($credentials))
            return response()->json(['status' => false, 'msg' => '아이디 혹은 비밀번호가 맞지 않습니다.'] , 200);
        $user = $request->user();
        if($user->tokens()->get() != '[]'){
            $user->tokens->first()->revoke();
        }
        $tokenResult = $user->createToken($request->email);
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        $user->partner;
        $isAdmin = ($request->type === 'admin' && $user->is_admin() ? true : false);
        return response()->json([
            'access_token' => $tokenResult->accessToken ,
            //??? 물어볼 부분
            'user' => $user->without('token')->first() ,
            'token_type' => 'Bearer' ,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'auth'=>$isAdmin
        ]);
    }
    /************************************************************************
     * @description : 로그아웃
     * @url         : /logout
     * @method      : POST
     * @return      : json
     ************************************************************************/
    public function destroy(Request $request) {
        error_log('destroy');
        Auth::guard('api')->user()->tokens()->first()->revoke();
        return response()->json([
            'msg' => '로그아웃 되었습니다.'
        ]);
    }
}
