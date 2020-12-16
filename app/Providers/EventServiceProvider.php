<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use \App\Listeners\ArticlesEventListener;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    //이벤트 구독자를 이용하면 하나의 리스너 클래스가 여러개의 이벤트를 구독하고 클래스 내부에서 이벤트를 처리할 수도 있다.
    protected $subscribe =[
        \App\Listeners\UsersEventListener::class,
    ];

    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SendMail::class => [
            SendMailFired::class,
        ],
        \App\Events\ArticlesEvent::class =>[
            \App\Listener\ArticlesEventListener::class,
        ],
        \Illuminate\Auth\Events\Login::class =>[
            \App\Listeners\UsersEventListener::class,
        ],
        \App\Events\CommentsEvent::class=>[
          \App\Listeners\CommentsEventListener::class,
        ],
        \App\Events\ModelChanged::class=>[
          \App\Listeners\CacheHandler::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        \Event::listen(
            \App\Events\ArticleCreated::class,
            ArticlesEventListener::class
        );
    }
}
