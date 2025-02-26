<?php

namespace Database\Seeders;

use App\Http\Controllers\GiatController;
use App\Models\report_sapu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportSapuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $giat = new GiatController();
        $length = fake()->numberBetween(1, 34);
        for($i = 0; $i < $length; $i++) {
            $lok1 = fake()->numberBetween(1, 34);
            $tgl = fake()->dateTimeBetween('-6 month', 'now')->format('Y-m-d');
            $giat->insertSampah($lok1, '', 1, $tgl);
            report_sapu::query()->insert([
                [
                    'polda_id' => $lok1,
                    'personil_ket' => '["Polsek Cikeusik","relawan pantas","nelayan"]',
                    'personil_jml' => '["10","29","15"]',
                    'personil_sat' => '["pers","pers","pers"]',
                    'lokasi' => 'pantai depokk parangtritis kretek bantul',
                    'sampah_organik_jml' => fake()->numberBetween(10, 100),
                    'sampah_organik_ket' => 'sampah daun dan ranting-ranting pohon, selanjutnya sampah di tanam untuk menjadi pupuk kompos.',
                    'sampah_anorganik_jml' => fake()->numberBetween(10, 100),
                    'sampah_anorganik_ket' => 'sampah plastik selanjutnya di kirim ke tempat pembuangan akhir.',
                    'tanggal' => $tgl,
                    'waktu' => fake()->time()
                ]
            ]);
        }
    }
}
