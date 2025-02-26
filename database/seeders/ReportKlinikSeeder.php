<?php

namespace Database\Seeders;

use App\Http\Controllers\GiatController;
use App\Models\report_klinik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportKlinikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $giat = new GiatController();
        $length = fake()->numberBetween(1, 34);
        for($i = 0; $i < $length; $i++) {
            $tgl = fake()->dateTimeBetween('-6 month', 'now')->format('Y-m-d');
            $giat->insertKlinik(fake()->numberBetween(1, 34), '', 1, $tgl);
            report_klinik::query()->insert([
                [
                    'polda_id' => fake()->numberBetween(1, 34),
                    'personil_jml' => '["'.fake()->numberBetween(3, 13).'"]',
                    'personil_sat' => '["pers"]',
                    'lokasi' => 'pantai depokk parangtritis kretek bantul',
                    'jml_peserta' => fake()->numberBetween(1, 10),
                    'keluhan_peserta' => 'batuk berdahak dan demam.',
                    'obat' => 'paracetamol dan ambroxol.',
                    'tanggal' => $tgl,
                    'waktu' => fake()->time()
                ]
            ]);
        }
    }
}
