<?php

namespace App\Listeners;

use App\Events\CommentsEvent;
use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class CommentsEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentsEvent  $event
     * @return void
     */
    //이벤트를 처리하는 함수
    public function handle(CommentsEvent $event)
    {
        //
        $comment = $event->comment;
        $comment->load('commentable');
        $to = $this->recipients($comment);

        if(!$to){
            return;
        }
        Mail::send('emails.comments.created', compact('comment'), function ($message) use($to){
            $message->to($to);
            $message->subject(
                sprintf('[%s] 새로운 댓글이 등록되었습니다.', config('app.name'))
            );
        });

    }
    //위에서 쓴 사용자 정의 함수
    private function recipients(Comment $comment)
    {
        static $to = [];

        if($comment->parent){
            $to[] = $comment->parent->user->email;
            $this->recipients($comment -> parent);
        }

        if($comment->commentable->notification){
            $to[] = $comment->commentable->user->email;
        }

        return array_unique($to);
    }
}
