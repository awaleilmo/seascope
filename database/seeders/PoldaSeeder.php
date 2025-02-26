<?php

namespace Database\Seeders;

use App\Models\polda;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PoldaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            polda::query()->insert([
                [
                    'id' => 1,
                    'name' => 'POLDA ACEH',
                    'kota' => 'ACEH',
                    'alamat' => '-',
                    'lokasi_id' => 1
                ],
                [
                    'id' => 2,
                    'name' => 'POLDA SUMUT',
                    'kota' => 'SUMATERA UTARA',
                    'alamat' => '-',
                    'lokasi_id' => 2
                ],
                [
                    'id' => 3,
                    'name' => 'POLDA SUMBAR',
                    'kota' => 'SUMATERA BARAT',
                    'alamat' => '-',
                    'lokasi_id' => 3
                ],
                [
                    'id' => 4,
                    'name' => 'POLDA SUMSEL',
                    'kota' => 'SUMATERA SELATAN',
                    'alamat' => '-',
                    'lokasi_id' => 4
                ],
                [
                    'id' => 5,
                    'name' => 'POLDA JAMBI',
                    'kota' => 'JAMBI',
                    'alamat' => '-',
                    'lokasi_id' => 5
                ],
                [
                    'id' => 6,
                    'name' => 'POLDA RIAU',
                    'kota' => 'RIAU',
                    'alamat' => '-',
                    'lokasi_id' => 6
                ],
                [
                    'id' => 7,
                    'name' => 'POLDA KEPRI',
                    'kota' => 'KEPULAUAN RIAU',
                    'alamat' => '-',
                    'lokasi_id' => 7
                ],
                [
                    'id' => 8,
                    'name' => 'POLDA BENGKULU',
                    'kota' => 'BENGKULU',
                    'alamat' => '-',
                    'lokasi_id' => 8
                ],
                [
                    'id' => 9,
                    'name' => 'POLDA BABEL',
                    'kota' => 'BANGKA BELITUNG',
                    'alamat' => '-',
                    'lokasi_id' => 9
                ],
                [
                    'id' => 10,
                    'name' => 'POLDA LAMPUNG',
                    'kota' => 'LAMPUNG',
                    'alamat' => '-',
                    'lokasi_id' => 10
                ],
                [
                    'id' => 11,
                    'name' => 'POLDA BANTEN',
                    'kota' => 'BANTEN',
                    'alamat' => '-',
                    'lokasi_id' => 11
                ],
                [
                    'id' => 12,
                    'name' => 'POLDA JABAR',
                    'kota' => 'JAWA BARAT',
                    'alamat' => '-',
                    'lokasi_id' => 12
                ],
                [
                    'id' => 13,
                    'name' => 'POLDA METRO JAYA',
                    'kota' => 'JAKARTA',
                    'alamat' => '-',
                    'lokasi_id' => 13
                ],
                [
                    'id' => 14,
                    'name' => 'POLDA JATENG',
                    'kota' => 'JAWA TENGAH',
                    'alamat' => '-',
                    'lokasi_id' => 14
                ],
                [
                    'id' => 15,
                    'name' => 'POLDA DIY',
                    'kota' => 'D.I YOGYAKARTA',
                    'alamat' => '-',
                    'lokasi_id' => 15
                ],
                [
                    'id' => 16,
                    'name' => 'POLDA JATIM',
                    'kota' => 'JAWA TIMUR',
                    'alamat' => '-',
                    'lokasi_id' => 16
                ],
                [
                    'id' => 17,
                    'name' => 'POLDA BALI',
                    'kota' => 'BALI',
                    'alamat' => '-',
                    'lokasi_id' => 17
                ],
                [
                    'id' => 18,
                    'name' => 'POLDA NTB',
                    'kota' => 'NUSA TENGGARA BARAT',
                    'alamat' => '-',
                    'lokasi_id' => 18
                ],
                [
                    'id' => 19,
                    'name' => 'POLDA NTT',
                    'kota' => 'NUSA TENGGARA TIMUR',
                    'alamat' => '-',
                    'lokasi_id' => 19
                ],
                [
                    'id' => 20,
                    'name' => 'POLDA KALBAR',
                    'kota' => 'KALIMANTAN BARAT',
                    'alamat' => '-',
                    'lokasi_id' => 20
                ],
                [
                    'id' => 21,
                    'name' => 'POLDA KALTENG',
                    'kota' => 'KALIMANTAN TENGAH',
                    'alamat' => '-',
                    'lokasi_id' => 21
                ],
                [
                    'id' => 22,
                    'name' => 'POLDA KALSEL',
                    'kota' => 'KALIMANTAN SELATAN',
                    'alamat' => '-',
                    'lokasi_id' => 22
                ],
                [
                    'id' => 23,
                    'name' => 'POLDA KALTIM',
                    'kota' => 'KALIMANTAN TIMUR',
                    'alamat' => '-',
                    'lokasi_id' => 23
                ],
                [
                    'id' => 24,
                    'name' => 'POLDA KALTARA',
                    'kota' => 'KALIMANTAN UTARA',
                    'alamat' => '-',
                    'lokasi_id' => 24
                ],
                [
                    'id' => 25,
                    'name' => 'POLDA SULUT',
                    'kota' => 'SULAWESI UTARA',
                    'alamat' => '-',
                    'lokasi_id' => 25
                ],
                [
                    'id' => 26,
                    'name' => 'POLDA GORONTALO',
                    'kota' => 'GORONTALO',
                    'alamat' => '-',
                    'lokasi_id' => 26
                ],
                [
                    'id' => 27,
                    'name' => 'POLDA SULTENG',
                    'kota' => 'SULAWESI TENGAH',
                    'alamat' => '-',
                    'lokasi_id' => 27
                ],
                [
                    'id' => 28,
                    'name' => 'POLDA SULBAR',
                    'kota' => 'SULAWESI BARAT',
                    'alamat' => '-',
                    'lokasi_id' => 28
                ],
                [
                    'id' => 29,
                    'name' => 'POLDA SULSEL',
                    'kota' => 'SULAWESI SELATAN',
                    'alamat' => '-',
                    'lokasi_id' => 29
                ],
                [
                    'id' => 30,
                    'name' => 'POLDA SULTRA',
                    'kota' => 'SULAWESI UTARA',
                    'alamat' => '-',
                    'lokasi_id' => 30
                ],
                [
                    'id' => 31,
                    'name' => 'POLDA MALUKU',
                    'kota' => 'MALUKU',
                    'alamat' => '-',
                    'lokasi_id' => 31
                ],
                [
                    'id' => 32,
                    'name' => 'POLDA MALUT',
                    'kota' => 'MALUKU UTARA',
                    'alamat' => '-',
                    'lokasi_id' => 32
                ],
                [
                    'id' => 33,
                    'name' => 'POLDA PAPUA',
                    'kota' => 'PAPUA',
                    'alamat' => '-',
                    'lokasi_id' => 33
                ],
                [
                    'id' => 34,
                    'name' => 'POLDA PABAR',
                    'kota' => 'PAPUA BARAT',
                    'alamat' => '-',
                    'lokasi_id' => 34
                ]
            ]);
    }
}
