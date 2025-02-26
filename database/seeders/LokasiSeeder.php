<?php

namespace Database\Seeders;

use App\Models\lokasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        lokasi::query()->insert([
            [
                "name" => "aceh",
                "alias" => "aceh"
            ],
            [
                "name" => "sumatra utara",
                "alias" => "sumut"
            ],
            [
                "name" => "sumatra barat",
                "alias" => "sumbar"
            ],
            [
                "name" => "sumatra selatan",
                "alias" => "sumsel"
            ],
            [
                "name" => "jambi",
                "alias" => "jambi"
            ],
            [
                "name" => "riau",
                "alias" => "riau"
            ],
            [
                "name" => "kepulauan riau",
                "alias" => "kepri"
            ],
            [
                "name" => "bengkulu",
                "alias" => "bengkulu"
            ],
            [
                "name" => "bangka belitung",
                "alias" => "babel"
            ],
            [
                "name" => "lampung",
                "alias" => "lampung"
            ],
            [
                "name" => "banten",
                "alias" => "banten"
            ],
            [
                "name" => "jawa barat",
                "alias" => "jabar"
            ],
            [
                "name" => "polda metro jaya",
                "alias" => "pmj"
            ],
            [
                "name" => "jawa tengah",
                "alias" => "jateng"
            ],
            [
                "name" => "daerah istimewa yogyakarta",
                "alias" => "diy"
            ],
            [
                "name" => "jawa timur",
                "alias" => "jatim"
            ],
            [
                "name" => "bali",
                "alias" => "bali"
            ],
            [
                "name" => "nusa tenggara barat",
                "alias" => "ntb"
            ],
            [
                "name" => "nusa tenggara timur",
                "alias" => "ntt"
            ],
            [
                "name" => "kalimantan barat",
                "alias" => "kalbar"
            ],
            [
                "name" => "kalimantan tengah",
                "alias" => "kalteng"
            ],
            [
                "name" => "kalimantan selatan",
                "alias" => "kalsel"
            ],
            [
                "name" => "kalimantan timur",
                "alias" => "kaltim"
            ],
            [
                "name" => "kalimantan utara",
                "alias" => "kaltara"
            ],
            [
                "name" => "sulawesi utara",
                "alias" => "sulut"
            ],
            [
                "name" => "gorontalo",
                "alias" => "gorontalo"
            ],
            [
                "name" => "sulawesi tengah",
                "alias" => "sulteng"
            ],
            [
                "name" => "sulawesi barat",
                "alias" => "sulbar"
            ],
            [
                "name" => "sulawesi selatan",
                "alias" => "sulsel"
            ],
            [
                "name" => "sulawesi utara",
                "alias" => "sultra"
            ],
            [
                "name" => "maluku",
                "alias" => "maluku"
            ],
            [
                "name" => "maluku utara",
                "alias" => "malut"
            ],
            [
                "name" => "papua",
                "alias" => "papua"
            ],
            [
                "name" => "papua barat",
                "alias" => "pabar"
            ]
        ]);
    }
}
