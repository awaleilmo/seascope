<?php

namespace App\Exports;

use App\Models\report_sapu;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class SapuExport implements FromQuery, WithHeadings, WithStyles, WithMapping, WithColumnWidths, WithDrawings
{
    use Exportable;

    private string $nameTgl;
    private string $poldaId;
    private string $namePolda;
    private string $nameLokasi;
    private string $tglDari;
    private string $tglSampai;
    private string $format;

    public function __construct($nameTgl, $poldaId, $namePolda, $nameLokasi, $tglDari, $tglSampai, $format)
    {
        $this->nameTgl = $nameTgl;
        $this->poldaId = $poldaId;
        $this->namePolda = $namePolda;
        $this->nameLokasi = $nameLokasi;
        $this->tglDari = $tglDari;
        $this->tglSampai = $tglSampai;
        $this->format = $format;
    }
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/kopsurat.jpg'));
        $drawing->setHeight(100);
        $drawing->setOffsetY(10);
        if($this->format === 'pdf'){
            $drawing->setOffsetX(3000);
            $drawing->setCoordinates('A1');
        }else{
            $drawing->setOffsetX(50);
            $drawing->setCoordinates('C1');
        }

        return $drawing;
    }
    public function query(): Relation|Builder|\Illuminate\Database\Query\Builder
    {
        return report_sapu::query()
            ->leftJoin('poldas', 'poldas.id', 'report_sapus.polda_id')
            ->select('poldas.name as polda_name',
                'personil_ket',
                'personil_jml',
                'personil_sat',
                'lokasi',
                'sampah_organik_ket',
                'sampah_organik_jml',
                'sampah_anorganik_ket',
                'sampah_anorganik_jml',
                'keterangan')
            ->selectRaw('ROW_NUMBER() OVER(ORDER BY name) AS num_row')
            ->where('report_sapus.polda_id', '=', $this->poldaId)
            ->where('report_sapus.tanggal', '>=', $this->tglDari)
            ->where('report_sapus.tanggal', '<=', $this->tglSampai);
    }
    public function sortPersonil($arr1, $arr2, $arr3): string
    {
        $result = '';
        for ($i = 0; $i < count($arr1); $i++) {
            if ($i == count($arr1) - 1) {
                $result .= ($i + 1) . '. ' . strtoupper($arr1[$i]) . " " . strtoupper($arr2[$i]) . " " . strtoupper($arr3[$i]);
                break;
            }
            $result .= ($i + 1) . '. ' . strtoupper($arr1[$i]) . " " . strtoupper($arr2[$i]) . " " . strtoupper($arr3[$i]) . "\n";
        }
        return $result;
    }
    public function sortHasil($str1, $str2): string
    {
        return $str1 . ' Kg' . "\n" . $str2;
    }
    public function map($report_sapu): array
    {
        $personil = $this->sortPersonil(
            json_decode($report_sapu->personil_ket),
            json_decode($report_sapu->personil_jml),
            json_decode($report_sapu->personil_sat)
        );
        $organik = $this->sortHasil(
            $report_sapu->sampah_organik_jml,
            $report_sapu->sampah_organik_ket
        );
        $anorganik = $this->sortHasil(
            $report_sapu->sampah_anorganik_jml,
            $report_sapu->sampah_anorganik_ket
        );
        return $report_sapu->num_row == 1 ? [
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [
                $report_sapu->num_row,
                $report_sapu->polda_name,
                $personil,
                $report_sapu->lokasi,
                $organik,
                $anorganik,
                $report_sapu->keterangan,
            ]
        ] : [
            $report_sapu->num_row,
            $report_sapu->polda_name,
            $personil,
            $report_sapu->lokasi,
            $organik,
            $anorganik,
            $report_sapu->keterangan,
        ];
    }
    public function headings(): array
    {
        return [
            [''],
            ['KEPOLISIAN NEGARA REPUBLIK INDONESIA'],
            [$this->nameLokasi],
            ['DIREKTORAT KEPOLISIAN PERAIRAN DAN UDARA'],
            ['LAPORAN HASIL GIAT PROGRAM UNGGULAN SAPU BERSIH SAMPAH PANTAI & LAUT DITPOLAIRUD '.$this->namePolda],
            [$this->nameTgl],
            [
                "NO",
                "POLDA",
                "JUMLAH PERSONIL",
                "LOKASI",
                "HASIL",
                "",
                "KET"
            ],
            [
                "",
                "",
                "",
                "",
                "SAMPAH ORGANIK",
                "SAMPAH ANORGANIK",
                ""
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 30,
            'C' => 30,
            'D' => 40,
            'E' => 50,
            'F' => 50,
            'G' => 30
        ];
    }

    /**
     * @throws Exception
     */
    public function styles(Worksheet $sheet): void
    {
        $getTotal = $this->query()->count();
        $count = ($getTotal + 8);
        $total = 'G' . $count; ;
//        Header
//        IMAGE
        $sheet->getStyle('A1')->getActiveSheet()->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getRowDimension('1')->setRowHeight(90);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getAlignment()->setVertical('center');

        $sheet->getStyle('A2')->getActiveSheet()->mergeCells('A2:D2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getRowDimension('2')->setRowHeight(20);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2')->getAlignment()->setVertical('center');

        $sheet->getStyle('A3')->getActiveSheet()->mergeCells('A3:D3');
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getRowDimension('3')->setRowHeight(20);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3')->getAlignment()->setVertical('center');

        $sheet->getStyle('A4')->getActiveSheet()->mergeCells('A4:D4');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle('A4')->getFont()->setUnderline(true);
        $sheet->getRowDimension('4')->setRowHeight(30);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A4')->getAlignment()->setVertical('top');

        $sheet->getStyle('A5')->getActiveSheet()->mergeCells('A5:G5');
        $sheet->getStyle('A5')->getFont()->setBold(true);
        $sheet->getStyle('A5')->getFont()->setUnderline(true);
        $sheet->getRowDimension('5')->setRowHeight(25);
        $sheet->getStyle('A5')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A5')->getAlignment()->setVertical('top');

        $sheet->getStyle('A6')->getActiveSheet()->mergeCells('A6:D6');
        $sheet->getRowDimension('6')->setRowHeight(20);
        $sheet->getStyle('A6')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('A6')->getAlignment()->setVertical('top');
        $sheet->getStyle('A6')->getAlignment()->setIndent(1);
//        End Header

//        Header Table
        $sheet->getStyle('D7')->getActiveSheet()->mergeCells('E7:F7');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('A7:A8');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('B7:B8');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('C7:C8');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('D7:D8');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('G7:G8');
        $sheet->getStyle('A7:G8')->getFont()->setBold(true);
        $sheet->getStyle('A7:G8')->getFont()->setSize($this->format === 'pdf' ? 10 : 12);
        $sheet->getStyle('A7:G8')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A7:G8')->getAlignment()->setVertical('center');
//        End Header Table

//        Body
        $sheet->getStyle('A9:' . $total)->getAlignment()->setVertical('top');
        $sheet->getStyle('A9:A'. $count)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A7:' . $total)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('B9:' . $total)->getAlignment()->setWrapText(true);
        $sheet->getStyle('B9:' . $total)->getAlignment()->setIndent(1);
        $sheet->getStyle('A7:' . $total)->getFont()->setSize($this->format === 'pdf' ? 10 : 11);
        $sheet->getStyle('A1')->getActiveSheet()->getPageSetup()->setPrintArea('A1:' . $total);
//        End Body
    }
}
