<?php

namespace App\Listeners;

use App\Events\ModelChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class CacheHandler
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
     * @param  ModelChanged  $event
     * @return bool|void
     */
    public function handle(ModelChanged $event)
    {
        //전체 캐시를 삭제 하는 메소드
        //return Cache::flush();
        //p.349 넘어온 태그를 가진 캐시들만 제거
        if(!taggable()){
            return Cache::flush();
        }

        return Cache::tags($event->cacheTags)->flush();
    }
}
