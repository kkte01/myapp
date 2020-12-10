<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UsersEventListener
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
    public function subscribe(\Illuminate\Events\Dispatcher $events){
        $events->listen(
            //이벤트를  이 class안에 있는 onUserCreated로 위임했다.
            \App\Events\UserCreated::class,
            __CLASS__ . '@onUserCreated'
        );
        $events->listen(
            //이벤트를  이 class안에 있는 onPasswordRemindCreated로 위임했다.
            \App\Events\PasswordRemindCreated::class,
            __CLASS__ . '@onPasswordRemindCreated'
        );
    }

    public function onUserCreated(\App\Events\UserCreated $event){
        $user = $event->user;
        \Mail::send('emails.auth.confirm', compact('user'), function($message) use ($user){
            $message->to($user->email);
            $message->subject(
                sprintf('[%s] 회원가입을 확인해 주세요', config('app.name'))
            );
        });
    }
    public function onPasswordRemindCreated(\App\Events\PasswordRemindCreated $event){

        \Mail::send('emails.passwords.reset', ['token' => $event->token], function($message) use ($event){
            $message->to($event->email);
            $message->subject(
                sprintf('[%s] 비밀번호를 초기화하세요', config('app.name'))
            );
        });
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        //
        $event->user->last_login = \Carbon\Carbon::now();

        return $event->user->save();
    }
}
