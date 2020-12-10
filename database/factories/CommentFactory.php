<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\App;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $articlesIds = Article::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();
        $faker = $this->faker;
        return [
            'content'=>$faker->paragraph,
            'commentable_type'=> Article::class,
            'commentable_id'=> function () use($faker, $articlesIds){
            return $faker->randomElement($articlesIds);
        },
            'user_id'=> function () use($faker, $userIds){
            return $faker->randomElement($userIds);
        }
        ];
    }
}
