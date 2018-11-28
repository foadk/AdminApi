<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'فواد',
            'last_name' => 'خالقی',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123')
        ]);
    }
}
