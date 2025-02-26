<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kia>
 */
class KiaFactory extends Factory
{
    private function numberToRoman($number)
    {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->date();
        $no_lap = 'LP/B/' . $this->faker->randomNumber(2).
            '/'.$this->numberToRoman(Carbon::parse($date)->format('m')).'/' .
            Carbon::parse($date)->format('Y') .
            '/SPKT.KORPOLAIRUD/BAHARKAM POLRI';

        return [
            'no_lp' => $no_lap,
            'tanggal_lp' => $date,
            'hasil_tangkapan' => $this->faker->sentence(1),
            'jenis_kasus' => $this->faker->sentence(1),
            'kronologis' => $this->faker->text(),

            'tersangka_nama' => json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),
            'tersangka_nik' => json_encode([$this->faker->randomNumber(), $this->faker->randomNumber(), $this->faker->randomNumber()]),
            'tersangka_warganegara' => json_encode([$this->faker->country, $this->faker->country, $this->faker->country]),
            'tersangka_suku' => json_encode([$this->faker->country, $this->faker->country, $this->faker->country]),
            'tersangka_jk' => json_encode(['Laki-laki', 'Perempuan', 'Laki-laki']),
            'tersangka_tempat_tgl_lahir' =>json_encode([
                $this->faker->city .', '. Carbon::parse($date)->format('d F Y'),
                $this->faker->city .', '. Carbon::parse($date)->format('d F Y'),
                $this->faker->city .', '. Carbon::parse($date)->format('d F Y')
            ]),
            'tersangka_umur' => json_encode([30, 50, 40]),
            'tersangka_agama' => json_encode(['islam', 'kristen', 'hindu']),
            'tersangka_pekerjaan' => json_encode(['PNS', 'TNI', 'Polri']),
            'tersangka_alamat' => json_encode([$this->faker->address, $this->faker->address, $this->faker->address]),

            'korban' => $this->faker->name(),

            'saksi_nama' => json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),
            'saksi_nik' => json_encode([$this->faker->randomNumber(), $this->faker->randomNumber(), $this->faker->randomNumber()]),
            'saksi_warganegara' => json_encode([$this->faker->city, $this->faker->city, $this->faker->city]),
            'saksi_suku' => json_encode([$this->faker->country, $this->faker->country, $this->faker->country]),
            'saksi_jk' => json_encode(['Laki-laki', 'Perempuan', 'Laki-laki']),
            'saksi_tempat_tgl_lahir' => json_encode([
                $this->faker->city .', '. Carbon::parse($date)->format('d F Y'),
                $this->faker->city .', '. Carbon::parse($date)->format('d F Y'),
                $this->faker->city .', '. Carbon::parse($date)->format('d F Y')
            ]),
            'saksi_umur' => json_encode([30, 50, 40]),
            'saksi_agama' => json_encode(['islam', 'kristen', 'hindu']),
            'saksi_pekerjaan' => json_encode(['PNS', 'TNI', 'Polri']),
            'saksi_alamat' => json_encode([$this->faker->address, $this->faker->address, $this->faker->address]),

            'melanggar_pasal' => $this->faker->sentence(15),
            'barang_bukti' => json_encode([$this->faker->sentence(5), $this->faker->sentence(5), $this->faker->sentence(5)]) ,
            'kerugian' => $this->faker->randomNumber(5),
            'penyidik' => $this->faker->name(),
            'penanganan_perkara' => $this->faker->text(15),
            'keterangan' => $this->faker->text(15),
        ];
    }
}
