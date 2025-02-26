<?php

namespace Database\Seeders;

use App\Http\Controllers\GiatController;
use App\Models\report_sambang;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportSambangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $giat = new GiatController();
        for($i = 0; $i < 1; $i++) {
            $lok1 = fake()->numberBetween(1, 34);
            $tgl1 = fake()->dateTimeBetween('-6 month', 'now')->format('Y-m-d');
            $giat->insertSambang($lok1, '', 1, $tgl1);
            report_sambang::query()->insert([
                [
                    'polda_id' => $lok1,
                    'personil_jml' => '["'.fake()->numberBetween(3, 10).'"]',
                    'personil_sat' => '["pers"]',
                    'lokasi' => fake()->address(),
                    'jarak' => fake()->numberBetween(20, 120),
                    'uraian' => 'BINLUH tentang penyalahgunaan nakoba dan penyalahgunaan obat-obatan',
                    'jml_peserta' => fake()->numberBetween(3, 60),
                    'tipe' => 1,
                    'tanggal' => $tgl1,
                    'waktu' => fake()->time()
                ]
            ]);
            $lok2 = fake()->numberBetween(1, 34);
            $tgl2 = fake()->dateTimeBetween('-6 month', 'now')->format('Y-m-d');
            $giat->insertSambang($lok2, '', 1, $tgl2);
            report_sambang::query()->insert([
                [
                    'polda_id' => $lok2,
                    'personil_jml' => '["'.fake()->numberBetween(3, 10).'"]',
                    'personil_sat' => '["pers"]',
                    'lokasi' => fake()->address(),
                    'jarak' => fake()->numberBetween(20, 120),
                    'uraian' => 'BINLUH tentang penyalahgunaan nakoba dan penyalahgunaan obat-obatan',
                    'jml_peserta' => fake()->numberBetween(3, 60),
                    'tipe' => 2,
                    'tanggal' => $tgl2,
                    'waktu' => fake()->time()
                ]
            ]);
            $lok3 = fake()->numberBetween(1, 34);
            $tgl3 = fake()->dateTimeBetween('-6 month', 'now')->format('Y-m-d');
            $giat->insertSambang($lok3, '', 1, $tgl3);
            report_sambang::query()->insert([
                [
                    'polda_id' => $lok3,
                    'personil_jml' => '["'.fake()->numberBetween(3, 10).'"]',
                    'personil_sat' => '["pers"]',
                    'lokasi' => fake()->address(),
                    'jarak' => fake()->numberBetween(20, 120),
                    'uraian' => 'BINLUH tentang penyalahgunaan nakoba dan penyalahgunaan obat-obatan',
                    'jml_peserta' => fake()->numberBetween(3, 60),
                    'tipe' => 3,
                    'tanggal' => $tgl3,
                    'waktu' => fake()->time()
                ]
            ]);
        }
    }
}
