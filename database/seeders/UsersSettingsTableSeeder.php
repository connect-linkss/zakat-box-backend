<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'phone' => '81390696',
                'password' => Hash::make('admin123'),
                'user_type' => 3,
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'guid' => Str::random(10),
                'email' => $user['email'],
                'phone' => $user['phone'],
                'active' => 1,
                'password' => $user['password'],
                'address' => "",
                'user_type' => $user['user_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
