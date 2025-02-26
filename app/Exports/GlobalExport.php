<?php

namespace App\Exports;

use App\Models\Globals;
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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class GlobalExport implements WithColumnWidths, WithStyles, FromCollection, WithMapping, WithDrawings, WithEvents, WithHeadings, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;

    private string $format;
    private string $periode;
    private string $tglDari;
    private string $tglSampai;


    public function collection()
    {
        // return Globals::all();
        return Globals::select('*')
            ->selectRaw('ROW_NUMBER() OVER(ORDER BY id) AS num_row')
            ->where('tanggal_lp', '>=', $this->tglDari)
            ->where('tanggal_lp', '<=', $this->tglSampai)
            ->get();

    }

    public function __construct(string $format = 'pdf', $periode, $tglDari, $tglSampai)
    {
        $this->format = $format;
        $this->periode = $periode;
        $this->tglDari = $tglDari;
        $this->tglSampai = $tglSampai;
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

    public function map($global): array
    {
        $barangbukti = $this->sortBukti(
            json_decode($global->barang_bukti),
        );

        $tersangka = $this->sortTersangka(
            json_decode($global->tersangka_nama),
            json_decode($global->tersangka_nik),
            json_decode($global->tersangka_warganegara),
            json_decode($global->tersangka_suku),
            json_decode($global->tersangka_jk),
            json_decode($global->tersangka_tempat_tgl_lahir),
            json_decode($global->tersangka_umur),
            json_decode($global->tersangka_agama),
            json_decode($global->tersangka_pekerjaan),
            json_decode($global->tersangka_alamat)
        );

        $saksi = $this->sortSaksi(
            json_decode($global->saksi_nama),
            json_decode($global->saksi_nik),
            json_decode($global->saksi_warganegara),
            json_decode($global->saksi_suku),
            json_decode($global->saksi_jk),
            json_decode($global->saksi_tempat_tgl_lahir),
            json_decode($global->saksi_umur),
            json_decode($global->saksi_agama),
            json_decode($global->saksi_pekerjaan),
            json_decode($global->saksi_alamat)
        );


        $data = [
            $global->num_row,
            $global->id_no_lp,
            $global->tanggal_lp,
            $global->hasil_tangkapan,
            $global->jenis_kasus,
            $global->kronologi,
            $tersangka,
            $global->korban,
            $saksi,
            $global->melanggar_pasal,
            $barangbukti,
            $global->kerugian,
            $global->ba_serah_terima,
            $global->keterangan,
        ];

        return $global->num_row == 1 ? [
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
            ['DATA PERKARA PENEGAKKAN HUKUM KAPAL PATROLI DAN SUBDIT GAKKUM'],
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
                'BA Serah Terima',
                'Keterangan',
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 50,
            'C' => 22,
            'D' => 30,
            'E' => 20,
            'F' => 50,
            'G' => 50,
            'H' => 20,
            'I' => 50,
            'J' => 40,
            'K' => 50,
            'L' => 20,
            'M' => 30,
            'N' => 20,
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
        $total = 'N' . $count;

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

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => [self::class, 'beforeWriting']
        ];
    }

    public static function beforeWriting(BeforeWriting $event)
    {
        $event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LEGAL);
        $event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
    }
}
