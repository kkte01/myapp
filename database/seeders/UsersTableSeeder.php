<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    //시딩 실행 시 run()을 실행한다.
    public function run()
    {
        //Str::random(int $length) = $length 바이트짜리 랜덤 문자열 반환.
        // User::create([
        //     'name'=> sprintf('%s %s', Str::random(3), Str::random(4)),
        //     'email'=> Str::random(10) . '@example.com',
        //     'password' => bcrypt('password')
        // ]);
        
        $users = User::factory()->count(5)->create(); 
    }
}
