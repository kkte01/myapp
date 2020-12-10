<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Article;
class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = User::all();

        $users ->each(function($users){
            //save 는 create 와 같은 기능이지만 받을 수 있는 인자의 타입이 다르다 save 는 객체 create 는 배열
            $users->articles()->save(
                Article::factory()->make()
            );
        });
    }
}
