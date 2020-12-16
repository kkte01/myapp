<?php

namespace Database\Seeders;

use App\Models\Vote;
use Illuminate\Database\Seeder;
use DB;
use App\Models\User;
use App\Models\Article;
use App\Models\Tag;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Models\Comment;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\User::factory(10)->create();
        if(config('database.default')!== 'sqlite'){
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        User::truncate();
        $this->call(UsersTableSeeder::class);

        Article::truncate();
        $this->call(ArticleTableSeeder::class);

        if(config('database.default') !== 'sqlite'){
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // 태그
        Tag::truncate();
        DB::table('article_tag')->truncate();
        $tags = config('project.tags');

        foreach(array_transpose($tags) as $slug => $names) {
            Tag::create([
                'name' => $names['ko'],
                'ko' => $names['ko'],
                'en' => $names['en'],
                'slug' => Str::slug($slug)
            ]);
        }
        $this->command->info('Seeded: tags table');

        //변수 선언
        $faker = app(Faker::class);
        $users = User::all();
        $articles = Article::all();
        $tags = Tag::all();

        //아티클과 태그 연결
        foreach($articles as $article){
            $article->tags()->Sync(
                $faker->randomElements(
                    $tags->pluck('id')->toArray()
                )
            );
        }
        $this->command->info('Seeded: article_tag table');

        //최상위 댓글`
        $articles->each(function ($article){
            $article->comments()->save(Comment::factory()->make());
            $article->comments()->save(Comment::factory()->make());
        });
        //자식 댓글
        $articles->each(function ($article) use ($faker){
            $commentsIds = Comment::pluck('id')->toArray();

            foreach (range(1,5) as $index){
                $article->comments()->save(
                    Comment::factory()->make([
                      'parent_id' => $faker->randomElement($commentsIds)
                    ])
                );
            }
        });
        $this->command->info('Seeded: comments table');

        //투표관련 기능
        $comments = Comment::all();

        $comments->each(function ($comment){
            $comment->votes()->save(Vote::factory()->make());
            $comment->votes()->save(Vote::factory()->make());
            $comment->votes()->save(Vote::factory()->make());
        });
        $this->command->info('Seeded: votes table');
    }
}
