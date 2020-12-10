<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
class SendMailController extends Controller
{
    //
    public function index(){
        $article = Article::with('user')->find(1);

        // Mail::send('emails.created', compact($article), function($message) use ($article){
        //     $message->to('kkte03@gmail.com');
        //     $message->subject('새 글이 등록 되었습니다. - ' . $article->title);
        // });
        //dd('Mail Send Sucessfully');
        Mail::send(
            'emails.created',
            compact('article'),
            function($message) use ($article){
                //받을 도메인 설정
                $message->to('kkte03@gmail.com');
                $message->subject('새 글이 등록되었습니다 -' . $article->title);
            }
        );
    }
}
