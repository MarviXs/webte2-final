<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(
            [
                'email' => 'user@example.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'password' => Hash::make('string'),
                'role' => 'admin',
            ]
        );

        User::create(
            [
                'email' => 'user2@example.com',
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'password' => Hash::make('string'),
                'role' => 'user',
            ]
        );
    }
}
