<?php

namespace Database\Seeders;

use App\Models\Gakkum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GakkumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gakkum::truncate();

        Gakkum::factory()
            ->count(5)
            ->create();
    }
}
