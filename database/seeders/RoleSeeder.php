<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->insert([
            [
                "name" => "superadmin",
                "sub" => 0,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "admin",
                "sub" => 0,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "operator",
                "sub" => 0,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "user",
                "sub" => 0,
                "created_at" => now(),
                "updated_at" => now()
            ],
        ]);
    }
}
