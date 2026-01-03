<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            [
                'name' => 'Muhammad Syafiq Aziz',
                'email' => 'm.syafiq.aziz@psychopnj.com',
                'email_verified_at' => null,
                'password' => Hash::make('31750321'),
                'active' => '1',
                'remember_token' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fahmi Ibrahim',
                'email' => 'fahmi.ibrahim@psychopnj.com',
                'email_verified_at' => null,
                'password' => Hash::make('31750321'),
                'active' => '1',
                'remember_token' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        $role = [
            [
                'role_id' => '1',
                'user_id' => '1',
                'user_type' => 'App\Models\User',
            ],
            [
                'role_id' => '1',
                'user_id' => '2',
                'user_type' => 'App\Models\User',
            ]
        ];

        DB::table('users')->insert($user);
        DB::table('role_user')->insert($role);
    }
}
