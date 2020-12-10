<?php

namespace Database\Factories;

use App\Models\Vote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        $up = $this->faker->randomElement([true,false]);
        $down = ! $up;
        $userIds = User::pluck('id')->toArray();
        return [
            //
            'up' => $up ? 1 : null,
            'down'=>$down ? 1 : null,
            'user_id'=> function () use($faker, $userIds){
                return $faker->randomElement($userIds);
            },
            'voted_at'=>now()
        ];
    }
}
