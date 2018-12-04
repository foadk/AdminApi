<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

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
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $users[] = [
                'name' => $firstName,
                'last_name' => $lastName,
                'sex' => $sex[rand(0, 1)],
                'email' => $firstName . '.' . $lastName . str_random(2) . '@gmail.com',
                'password' => Hash::make('secret'),
                'mobile' => $faker->phoneNumber,
            ];
        }

        \App\User::insert($users);
    }
}
