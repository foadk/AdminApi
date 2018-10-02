<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $display = [true, true, true, false];
        $news = [];

        for ($i = 1; $i < 100; $i++) {
            $news[] = [
                'title' => $faker->firstName,
                'description' => $faker->realText($maxNbChars = 100, $indexSize = 2),
                'content' => clean(file_get_contents('http://loripsum.net/api')),
                'display' => $display[rand(0, 3)],
            ];
        }

        \App\News::insert($news);
    }
}
