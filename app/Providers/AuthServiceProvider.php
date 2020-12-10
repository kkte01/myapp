<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {   //최고관리자 선언
        //확인을 위해 isAdmin 선언
        //before()는 다른 권한 검사를 처리하기 전에 먼저 실행된다. bool값을 반환한다.
        Gate::before(function ($user) {
            if ($user->isAdmin()) return true;
        });
        //$this->registerPolicies();

        //로그인한 사용자만 CRUD가 가능하지만 자신이 작성하지 않은 글도 수정 또는 삭제가 가능하기떄문에 여기서 인가기능을 통해 방어
        //이것은 이제 컨트롤러에서 사용하면 된다.
        Gate::define('update', function ($user, $model) {
            return $user->id === $model->user_id;
        });

        Gate::define('delete', function ($user, $model) {
            return $user->id === $model->user_id;
        });
        //

    }
}
