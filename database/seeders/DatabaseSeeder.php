<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'name' => 'MGS',
            'email' => 'mgs@mgs.com',
            'type' => 'admin',
            'balance' => 100,
            'email_verified_at' => now(),
            'password' => Hash::make('mgs'), // password
            'remember_token' => Str::random(10),
        ]);

        \App\Models\User::factory(5)->create();
    }
}
