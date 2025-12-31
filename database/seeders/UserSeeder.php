<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Test User',
                'email' => 'test@example.com',
                'email_verified_at' => '2025-05-11 13:35:02',
                'password' => Hash::make('test123'),
                'remember_token' => 'THve0AjBgg',
                'role' => 'Admin',
                'created_at' => '2025-05-11 13:35:02',
                'updated_at' => '2025-05-11 13:35:02',
            ],
            [
                'id' => 4,
                'name' => 'Admin Gudang',
                'email' => 'admin@example.com',
                'email_verified_at' => null,
                'password' => Hash::make('test123'),
                'remember_token' => null,
                'role' => 'Admin',
                'created_at' => '2025-05-11 13:43:25',
                'updated_at' => '2025-05-11 13:43:25',
            ],
            [
                'id' => 5,
                'name' => 'Vendor User',
                'email' => 'vendor@example.com',
                'email_verified_at' => null,
                'password' => Hash::make('test123'),
                'remember_token' => null,
                'role' => 'Vendor',
                'created_at' => '2025-05-11 13:43:25',
                'updated_at' => '2025-05-11 13:43:25',
            ],
            [
                'id' => 6,
                'name' => 'Supervisor Gudang',
                'email' => 'supervisor@example.com',
                'email_verified_at' => null,
                'password' => Hash::make('test123'),
                'remember_token' => null,
                'role' => 'Supervisor',
                'created_at' => '2025-05-11 13:43:26',
                'updated_at' => '2025-05-11 13:43:26',
            ],
        ]);
    }
}
