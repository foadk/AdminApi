<?php

use Illuminate\Database\Seeder;

class NewsCatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topCats = [
            ['title' => 'عمومی'],
            ['title' => 'اختصاصی']
        ];

        \App\NewsCat::insert($topCats);

        $generalId = \App\NewsCat::where('title', 'عمومی')->first()->id;
        $specialisedId = \App\NewsCat::where('title', 'اختصاصی')->first()->id;

        $cats = [
            ['title' => 'ورزشی', 'news_cat_id' => $generalId],
            ['title' => 'اقتصادی', 'news_cat_id' => $specialisedId],
            ['title' => 'سیاسی', 'news_cat_id' => $specialisedId]
        ];

        \App\NewsCat::insert($cats);


    }
}
