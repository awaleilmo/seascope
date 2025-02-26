<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->insert([
            [
                'name' => 'Admin',
                'username' => 'admin@12',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin1731'),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
