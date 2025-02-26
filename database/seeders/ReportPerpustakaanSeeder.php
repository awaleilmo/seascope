<?php

namespace Database\Seeders;

use App\Http\Controllers\GiatController;
use App\Models\report_perpustakaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportPerpustakaanSeeder extends Seeder
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
            $giat->insertPerpustakaan(fake()->numberBetween(1, 34), '', 1, $tgl);
            report_perpustakaan::query()->insert([
                [
                    'polda_id' => fake()->numberBetween(1, 34),
                    'personil_jml' => '["'.fake()->numberBetween(4, 12).'"]',
                    'personil_sat' => '["pers"]',
                    'lokasi' => 'Dermaga Unit Kapal Subdit Patroli',
                    'jml_peserta' => fake()->numberBetween(1, 10),
                    'asal_peserta' => 'TK. Kemala Bhayangkara Bengkulu.',
                    'hasil' => 'anak-anak senang dengan adanya perpustakaan terapung dan berharap bukunya lebih banyak untuk bacaan.',
                    'tanggal' => $tgl,
                    'waktu' => fake()->time()
                ]
            ]);
        }
    }
}
