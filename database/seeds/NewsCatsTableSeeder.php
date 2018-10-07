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
        $cats = [
            ['title' => 'عمومی'],
            ['title' => 'اختصاصی'],
            ['title' => 'ورزشی'],
            ['title' => 'اقتصادی'],
            ['title' => 'سیاسی']
        ];

        \App\NewsCat::insert($cats);
    }
}
