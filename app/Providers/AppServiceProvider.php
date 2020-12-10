<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //뷰 컴포저 이용 모든 뷰에서 필요하기 때문에 이용한다. 변수에 담아서 보낼경우 깔끔하지가 않다.
        view()->composer('*', function($view) {
            $allTags = \Cache::rememberForever('tags.list', function(){
                return \App\Models\Tag::all();
            });

            $view->with(compact('allTags'));
            $view->with('currentUser', auth()->user());
        });
    }
}
