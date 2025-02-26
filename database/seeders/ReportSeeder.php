<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(
            [
                ReportKlinikSeeder::class,
                ReportSapuSeeder::class,
                ReportPerpustakaanSeeder::class,
                ReportPolisiRwSeeder::class,
                ReportSambangSeeder::class
            ]);
    }
}
