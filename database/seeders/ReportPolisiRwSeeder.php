<?php

namespace Database\Seeders;

use App\Http\Controllers\GiatController;
use App\Models\report_rw;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportPolisiRwSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $giat = new GiatController();
        for ($i = 0; $i < 1; $i++) {
            $lok1 = fake()->numberBetween(1, 34);
            $tgl1 = fake()->dateTimeBetween('-6 month', 'now')->format('Y-m-d');
            $giat->insertRw($lok1, '', 1, $tgl1);
            report_rw::query()->insert([
                [
                    'polda_id' => $lok1,
                    'personil_jml' => '["1"]',
                    'personil_satu' => '["pers"]',
                    'sambang' => 'Polisi RW Aipda Rusidi melaksanakan sambangkepada Bapak Amirudin no hp. 08213482000warga RW 01 Kel. Karanganyar, Kec Tugu, Semarang. Memberikan himbauan kepada warga untuk selalu hidup rukun saling hormat menghormati sesama warga masyarakat. Demi terciptanya kamtibmas yang kondusif. Diharapkanmasyarakat RW 01 selalu melaporkan setiap ada kejadian yang menonjol kepada Polisi RW atau Bhabinkamtibmas dan perangkat desa sehingga setiap ada masalah bisa ditangani dengan secepatnya.',
                    'pemecahaan' => 'Tidak terdapat permasalahan sosial.',
                    'informasi' => '',
                    'penanganan' => '',
                    'tanggal' => $tgl1,
                    'waktu' => fake()->time()
                ]
            ]);
            $lok2 = fake()->numberBetween(1, 34);
            $tgl2 = fake()->dateTimeBetween('-6 month', 'now')->format('Y-m-d');
            $giat->insertRw($lok2, '', 1, $tgl2);
            report_rw::query()->insert([
                [
                    'polda_id' => $lok2,
                    'personil_jml' => '["1"]',
                    'personil_satu' => '["pers"]',
                    'sambang' => 'Polisi RW Aiptu Kasmanto melaksanakan Giat sambang kepada Bapak Daroji no hp 0857744313115 warga RW 01, Kel.Bandarharjo, Kec.Semarang Utara.Memberikan himbauan kepada warga untuk selalu hidup rukun saling hormat menghormati sesama warga masyarakat. Demi terciptanya kamtibmas yang kondusif dan mendapat keluhan adanya tawuran.',
                    'pemecahaan' => 'keluhan masalah dari masyarakat Yaitu: adanya tawuran antar anak setiap Malam Minggu di sekitar Pelabuhan di takutkan Masuk ke wilayah pemukiman wilayah RW 01.Penyelesaian:Polisi RW berkoordinasi dengan Bhabinkamtibmasdan mengajak warga untuk melaksanakan ronda pada setiap malam untuk mencegah terjadinya tawuran.',
                    'informasi' => '',
                    'penanganan' => '',
                    'tanggal' => $tgl2,
                    'waktu' => fake()->time()
                ]
            ]);
            $lok3 = fake()->numberBetween(1, 34);
            $tgl3 = fake()->dateTimeBetween('-6 month', 'now')->format('Y-m-d');
            $giat->insertRw($lok3, '', 1, $tgl3);
            report_rw::query()->insert([
                [
                    'polda_id' => $lok3,
                    'personil_jml' => '["1"]',
                    'personil_satu' => '["pers"]',
                    'sambang' => 'Polisi RW Bripka Suranto Melaksanakan Giatsambang kepada bapak Wijoyo no hp 085865701855 warga RT 03 Tambak Lorok, RW 14, Kel. Tanjung Emas, Kec Semarang Utara. Polisi RW Memberikan himbauan kamtibmas untuk selalu menjaga kerukunan antar warga serta selalu menjaga kamtibmas.',
                    'pemecahaan' => '',
                    'informasi' => '',
                    'penanganan' => 'terjadi pertengkaran dalam keluarga antara kakak dan adik tentang pernikahan adik paling bungsu. Polisi RW mengambil langkah langkah:
1.mempertemukan kedua belah pihak disaksikan bapak RT setempat:
2.menyelesaikan masalah dengan musyawarah bersama kedua belah pihak;
3.memberikan gambaran dan masukan bahwa masalah keluarga tidak ada untungnya di selesaikan dengan kekerasan, karena akan merugikan kedua belah pihak
Alhamdulillah bisa dimengerti dan kedua belah pihak dapat berdamai kembali.',
                    'tanggal' => $tgl3,
                    'waktu' => fake()->time()
                ]
            ]);
            $lok4 = fake()->numberBetween(1, 34);
            $tgl4 = fake()->dateTimeBetween('-6 month', 'now')->format('Y-m-d');
            $giat->insertRw($lok4, '', 1, $tgl4);
            report_rw::query()->insert([
                [
                    'polda_id' => $lok4,
                    'personil_jml' => '["1"]',
                    'personil_satu' => '["pers"]',
                    'sambang' => 'Polisi RW Aipda Lalu Agus Melaksanakan Giat sambang kepada bapak Ali Mahmudi no hp. 081575816516 warga RT 04 Tambak Lorok, RW 14, Kel. Tanjung Emas, Kec Semarang Utara. Polisi RW menghimbau untuk memasang kunci pengaman tambahan pada kendaraan bermotor dan mengajak warga untuk melaksanakan ronda untuk menjaga keamanan kamtibmas. Dan segera melaporkan kepada kantor polisi terdekat apabila ada kejadian gangguan kamtibmas.',
                    'pemecahaan' => '',
                    'informasi' => 'sering terjadinyapencurian kendaraaansepeda motor. Polisi RW menghimbau untuk memasang kunci pengaman tambahan pada kendaraan bermotor dan mengajak warga untuk melaksanakan ronda untuk menjaga keamanan kamtibmas. Dan segera melaporkan kepada kantor polisi terdekat apabila ada kejadian gangguan kamtibmas.',
                    'penanganan' => '',
                    'tanggal' => $tgl4,
                    'waktu' => fake()->time()
                ]
            ]);
        }
    }
}
