<?php

namespace App\Providers;

use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Optimus\Optimus;

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
        $this->app->singleton('optimus', function (){
            return new Optimus(1081648643, 1592112299, 2021739750);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //순서 주의 런타임에 설정한 언어를 $currentLocale에 담아야 한다.
        //app()->setLocale(...); 호출이 먼저 나오고 뷰 컴포저를 선언해야한다.
        //p. 350 code 30-1
        //app()->setLocale('ko');

        //p.356 code 30-12
        //설정 된 쿠키 값 설정
        if($locale = request()->cookie('locale__myapp')){
            //쿠키 값 복호화 해 설정값 확인
            App::setLocale(Crypt::decryptString($locale));
            //dd(app()->getLocale());
        }
        Carbon::setLocale(App::getLocale());
        //뷰 컴포저 이용 모든 뷰에서 필요하기 때문에 이용한다. 변수에 담아서 보낼경우 깔끔하지가 않다.
        view()->composer('*', function($view) {

            //dd($currentLocale);
            //$currentUrl = request()->fullUrl();
            $currentUser = auth()->user();
            $allTags = Cache::rememberForever('tags.list', function(){
                return Tag::all();
            });
            $currentLocale = App::getLocale();
            $currentUrl = current_url();

            $view->with(compact('allTags', 'currentLocale', 'currentUrl', 'currentUser'));
            //$view->with('currentUser', auth()->user());
        });


    }
}
