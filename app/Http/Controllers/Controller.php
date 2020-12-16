<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $cache;

    public function __construct()
    {
        $this->cache = app('cache');
        if((new \ReflectionClass($this))->implementsInterface(Cacheable::class) and taggable()){
            $this->cache = app('cache')->tags($this->cacheTags());
        }
    }

    protected function cache($key, $minutes, $query, $method, ...$args){

        //$cache = taggable() ? app('cache')->tags('???') : app('cache');

        $args = (!empty($args)) ? implode(',', $args) : null;

        if(config('project.cache') === false){
            return $query->{$method}($args);
        }
        //return Cache::remember에서 변경
        //p.347에서 $this 추가
        return $this->cache->remember($key, $minutes, function () use ($query, $method, $args){
            //dd('캐시되면 촐력안됨');
            return $query->{$method}($args);
        });
    }
}
