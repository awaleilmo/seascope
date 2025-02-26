<?php

namespace App\Exports;

use App\Models\Gakkum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GakkumExport implements WithColumnWidths, WithStyles, FromCollection, WithMapping, WithDrawings, WithEvents, WithHeadings
{
    use Exportable;

    private string $format;
    private string $periode;
    private string $tglDari;
    private string $tglSampai;
    private Builder $table;
    private string $title;

    public function collection()
    {
        return $this->table->select('*')
            ->selectRaw('ROW_NUMBER() OVER(ORDER BY id) AS num_row')
            ->where('tanggal_lp', '>=', $this->tglDari)
            ->where('tanggal_lp', '<=', $this->tglSampai)
            ->get();
    }

    public function __construct(Builder $table, string $format = 'pdf', $periode, $tglDari, $tglSampai, $title = '-')
    {
        $this->format = $format;
        $this->periode = $periode;
        $this->tglDari = $tglDari;
        $this->tglSampai = $tglSampai;
        $this->table = $table;
        $this->title = $title;
    }

    public function sortTersangka($arr1, $arr2, $arr3, $arr4, $arr5, $arr6, $arr7, $arr8, $arr9, $arr10): string
    {
        $result = '';
        for ($i = 0; $i < count($arr1); $i++) {
            if ($i == count($arr1) - 1) {
                $result .= ($i + 1) . '. ' . strtoupper($arr1[$i])
                . "\n" . strtoupper($arr2[$i])
                . "\n" . strtoupper($arr3[$i])
                . "\n" . strtoupper($arr4[$i])
                . "\n" . strtoupper($arr5[$i])
                . "\n" . strtoupper($arr6[$i])
                . "\n" . strtoupper($arr7[$i])
                . " Thn \n" . strtoupper($arr8[$i])
                . "\n" . strtoupper($arr9[$i])
                . "\n" . strtoupper($arr10[$i]);
                break;
            }
            $result .= ($i + 1) . '. ' . strtoupper($arr1[$i])
            . "\n" . strtoupper($arr2[$i])
            . "\n" . strtoupper($arr3[$i])
            . "\n" . strtoupper($arr4[$i])
            . "\n" . strtoupper($arr5[$i])
            . "\n" . strtoupper($arr6[$i])
            . "\n" . strtoupper($arr7[$i])
            . " Thn \n" . strtoupper($arr8[$i])
            . "\n" . strtoupper($arr9[$i])
            . "\n" . strtoupper($arr10[$i])
            . "\n";
        }
        return $result;
    }

    public function sortSaksi($arr1, $arr2, $arr3, $arr4, $arr5, $arr6, $arr7, $arr8, $arr9, $arr10): string
    {
        $result = '';
        for ($i = 0; $i < count($arr1); $i++) {
            if ($i == count($arr1) - 1) {
                $result .= ($i + 1) . '. ' . strtoupper($arr1[$i])
                . "\n" . strtoupper($arr2[$i])
                . "\n" . strtoupper($arr3[$i])
                . "\n" . strtoupper($arr4[$i])
                . "\n" . strtoupper($arr5[$i])
                . "\n" . strtoupper($arr6[$i])
                . "\n" . strtoupper($arr7[$i])
                . " Thn \n" . strtoupper($arr8[$i])
                . "\n" . strtoupper($arr9[$i])
                . "\n" . strtoupper($arr10[$i]);
                break;
            }
            $result .= ($i + 1) . '. ' . strtoupper($arr1[$i])
            . "\n" . strtoupper($arr2[$i])
            . "\n" . strtoupper($arr3[$i])
            . "\n" . strtoupper($arr4[$i])
            . "\n" . strtoupper($arr5[$i])
            . "\n" . strtoupper($arr6[$i])
            . "\n" . strtoupper($arr7[$i])
            . " Thn \n" . strtoupper($arr8[$i])
            . "\n" . strtoupper($arr9[$i])
            . "\n" . strtoupper($arr10[$i])
            . "\n";
        }
        return $result;
    }

    public function sortBukti($arr1): string
    {
        $result = '';
        for ($i = 0; $i < count($arr1); $i++) {
            if ($i == count($arr1) - 1) {
                $result .= ($i + 1) . '. ' . strtoupper($arr1[$i]);
                break;
            }
            $result .= ($i + 1) . '. ' . strtoupper($arr1[$i]) . "\n";
        }
        return $result;
    }

    public function map($gakkum): array
    {

        $tersangka = $this->sortTersangka(
            json_decode($gakkum->tersangka_nama),
            json_decode($gakkum->tersangka_nik),
            json_decode($gakkum->tersangka_warganegara),
            json_decode($gakkum->tersangka_suku),
            json_decode($gakkum->tersangka_jk),
            json_decode($gakkum->tersangka_tempat_tgl_lahir),
            json_decode($gakkum->tersangka_umur),
            json_decode($gakkum->tersangka_agama),
            json_decode($gakkum->tersangka_pekerjaan),
            json_decode($gakkum->tersangka_alamat)
        );

        $saksi = $this->sortSaksi(
            json_decode($gakkum->saksi_nama),
            json_decode($gakkum->saksi_nik),
            json_decode($gakkum->saksi_warganegara),
            json_decode($gakkum->saksi_suku),
            json_decode($gakkum->saksi_jk),
            json_decode($gakkum->saksi_tempat_tgl_lahir),
            json_decode($gakkum->saksi_umur),
            json_decode($gakkum->saksi_agama),
            json_decode($gakkum->saksi_pekerjaan),
            json_decode($gakkum->saksi_alamat)
        );

        $barang_bukti = $this->sortBukti(
            json_decode($gakkum->barang_bukti),
        );


        $data = [
            $gakkum->num_row,
            $gakkum->no_lp,
            $gakkum->tanggal_fotmated,
            $gakkum->hasil_tangkapan,
            $gakkum->jenis_kasus,
            $gakkum->kronologis,
            $tersangka,
            $gakkum->korban,
            $saksi,
            $gakkum->melanggar_pasal,
            $barang_bukti,
            $gakkum->kerugian_formated,
            $gakkum->penyidik,
            $gakkum->penanganan_perkara,
            $gakkum->keterangan,
        ];

        return $gakkum->num_row == 1 ? [
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            $data
        ] : $data;
    }

    public function headings(): array
    {
        return [
            [],
            ['DIREKTORAT KEPOLISIAN PERAIRAN'],
            ['SUBDIT GAKKUM'],
            [],
            [Str::upper($this->title)],
            [Str::upper($this->periode)],
            [],
            [
                'No',
                'Nomor Laporan',
                'Tanggal',
                'Hasil Tangkapan',
                'Jenis Kasus',
                'Kronologis',
                'Tersangka',
                'Korban',
                'Saksi',
                'Melanggar Pasal',
                'Barang Bukti',
                'Kerugian',
                'Penyidik',
                'Penanganan Perkara',
                'Keterangan',
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 50,
            'C' => 22,
            'D' => 20,
            'E' => 15,
            'F' => 50,
            'G' => 50,
            'H' => 20,
            'I' => 40,
            'J' => 40,
            'K' => 60,
            'L' => 20,
            'M' => 20,
            'N' => 20,
            'O' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {

        // image
        $sheet->getRowDimension('1')->setRowHeight(90);
        $sheet->getStyle('A1')->getActiveSheet()->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getAlignment()->setVertical('center');

        // title
        $sheet->getRowDimension('2')->setRowHeight(20);
        $sheet->getStyle('A2')->getActiveSheet()->mergeCells('A2:D2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getFont()->setSize(18);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2')->getAlignment()->setVertical('center');

        $sheet->getRowDimension('3')->setRowHeight(20);
        $sheet->getStyle('A3')->getActiveSheet()->mergeCells('A3:D3');
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getStyle('A3')->getFont()->setSize(18);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3')->getAlignment()->setVertical('center');

        $sheet->getRowDimension('5')->setRowHeight(20);
        $sheet->getStyle('A5')->getActiveSheet()->mergeCells('A5:O5');
        $sheet->getStyle('A5')->getFont()->setBold(true);
        $sheet->getStyle('A5')->getFont()->setSize(18);
        $sheet->getStyle('A5')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A5')->getAlignment()->setVertical('center');

        $sheet->getRowDimension('6')->setRowHeight(20);
        $sheet->getStyle('A6')->getActiveSheet()->mergeCells('A6:O6');
        $sheet->getStyle('A6')->getFont()->setBold(true);
        $sheet->getStyle('A6')->getFont()->setSize(18);
        $sheet->getStyle('A6')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A6')->getAlignment()->setVertical('center');

        // header
        $sheet->getRowDimension('8')->setRowHeight(20);
        $sheet->getStyle('A8:O8')->getFont()->setBold(true);
        $sheet->getStyle('A8')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A8:O8')->getAlignment()->setVertical('center');

        // Body
        $getTotal = $this->collection()->count();
        $count = ($getTotal + 8);
        $total = 'O' . $count;;

        $sheet->getStyle('A1')->getActiveSheet()->getPageSetup()->setPrintArea('A1:' . $total);

        $sheet->getStyle('A8:' . $total)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A8:' . $total)->getFont()->setSize($this->format === 'pdf' ? 10 : 11);


        $sheet->getStyle('A9:' . $total)->getAlignment()->setVertical('top');
        $sheet->getStyle('A9:A' . $count)->getAlignment()->setHorizontal('center');

        $sheet->getStyle('B9:' . $total)->getAlignment()->setWrapText(true);
        $sheet->getStyle('B9:' . $total)->getAlignment()->setIndent(1);

        $sheet->getStyle('L9:L' . $count)->getAlignment()->setHorizontal('right');


    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/kopsurat.jpg'));
        $drawing->setHeight(100);
        $drawing->setOffsetY(10);
        if ($this->format === 'pdf') {
            $drawing->setOffsetX(3000);
            $drawing->setCoordinates('A1');
        } else {
            $drawing->setOffsetX(230);
            $drawing->setCoordinates('B1');
        }

        return $drawing;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => [self::class, 'beforeWriting']
        ];
    }

    public static function beforeWriting(BeforeWriting $event)
    {
        $event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
    }
}
