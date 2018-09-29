<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $sex = ['male', 'female'];

        $users = [];

        for ($i = 1; $i < 1000; $i++) {
            $users[] = [
                'name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'sex' => $sex[rand(0, 1)],
                'email' => str_random(10) . '@gmail.com',
                'password' => bcrypt('secret'),
                'mobile' => $faker->phoneNumber,
            ];
        }

        \App\User::insert($users);
    }
}
