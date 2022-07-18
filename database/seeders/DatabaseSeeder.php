<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'access_level' => 2,
            'password' =>  Hash::make('password'),
        ]);
    }
}
