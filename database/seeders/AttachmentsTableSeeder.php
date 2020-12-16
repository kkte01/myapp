<?php

namespace Database\Seeders;

use App\Models\Attachment;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AttachmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //$this->command->warn("File saved: {$filename}");
        foreach(range(1, 10) as $index) {
            Attachment::factory()->make()->save();
        }
    }
}
