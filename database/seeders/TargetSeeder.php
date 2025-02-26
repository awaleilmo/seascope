<?php

namespace Database\Seeders;

use App\Models\TargetPU;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TargetPU::query()->insert([
            [
                "tahun" => 2023,
                'klinik' => 180,
                'perpustakaan' => 80,
                'rw' => 120,
                'sambang' => 170,
                'sapu' => 220
            ],
            [
                "tahun" => 2024,
                'klinik' => 200,
                'perpustakaan' => 90,
                'rw' => 130,
                'sambang' => 180,
                'sapu' => 240
            ]
        ]);
    }
}
