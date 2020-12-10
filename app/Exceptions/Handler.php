<?php

namespace App\Exceptions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    //p.132 예외처리
    // public function report(Exception $e)
    // {
    //     parent::report($e);
    // }

    // public function render($request, Exception $exception){
    //     //p.133 code 15-4
    //     if(app()->environmemt('production')){
    //         if($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException){
    //             return response(view('errors.notice',[
    //                 'title'=>'찾을 수 없습니다.', 
    //                 'description' => '죄송합니다! 요청하신 페이지가 없습니다.'
    //             ]), 404);
    //         }
    //     }

    //     return parent::render($request, $exception);
    // }
    //
    public function render($request, Throwable $exception)
    {
        if(app()->environment('production')){
            $statusCode =  400;
            $title = '죄송합니다. :( ';
            $desciption = '에러가 발생했습니다. ';
        
            if($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException or $exception instanceof \Symfony\Component\Httpkerner\Exception\NotFoundHttpException){
                $statusCode = 404;
                $desciption = $exception->getMessage() ?: '요청하신 페이지가 없습니다.';
            }
            return response(view('error.notice', [
                'title'=> $title,
                'description' => $desciption,
            ], $statusCode));
        }
        return parent::render($request, $exception);
    }
    //ArticleController에서 로그인한 사람만 CRUD 가능하게한 예외처리부분
    protected function unauthenticated($request, AuthenticationException $exception){

        if($request->expectsJson()){
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        
        return redirect()->guest(route('sessions.create'));
    }
}
