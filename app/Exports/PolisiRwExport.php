<?php

namespace App\Exports;

use App\Models\report_rw;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PolisiRwExport implements FromQuery, WithHeadings, WithStyles, WithMapping, WithColumnWidths, WithDrawings
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
            $drawing->setOffsetX(10);
            $drawing->setCoordinates('D1');
        }

        return $drawing;
    }
    public function query(): Relation|Builder|\Illuminate\Database\Query\Builder
    {
        return report_rw::query()
            ->leftJoin('poldas', 'poldas.id', 'report_rws.polda_id')
            ->select('poldas.name as polda_name',
                'personil_jml',
                'personil_satu',
                'sambang',
                'pemecahaan',
                'informasi',
                'penanganan',
                'keterangan')
            ->selectRaw('ROW_NUMBER() OVER(ORDER BY name) AS num_row')
            ->where('report_rws.polda_id', '=', $this->poldaId)
            ->where('report_rws.tanggal', '>=', $this->tglDari)
            ->where('report_rws.tanggal', '<=', $this->tglSampai);
    }
    public function sortPersonil($arr1, $arr2): string
    {
        $result = '';
        for ($i = 0; $i < count($arr1); $i++) {
            $result .= strtoupper($arr1[$i]) .  "\n";
        }
        return $result;
    }
    public function map($report_rw): array
    {
        $personil = $this->sortPersonil(
            json_decode($report_rw->personil_jml),
            json_decode($report_rw->personil_sat)
        );
        return $report_rw->num_row == 1 ? [
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [
                $report_rw->num_row,
                $report_rw->polda_name,
                $personil,
                $report_rw->sambang,
                $report_rw->pemecahaan,
                $report_rw->informasi,
                $report_rw->penanganan,
                $report_rw->keterangan,
            ]
        ] : [
            $report_rw->num_row,
            $report_rw->polda_name,
            $personil,
            $report_rw->sambang,
            $report_rw->pemecahaan,
            $report_rw->informasi,
            $report_rw->penanganan,
            $report_rw->keterangan,
        ];
    }
    public function headings(): array
    {
        return [
            [''],
            ['KEPOLISIAN NEGARA REPUBLIK INDONESIA'],
            [$this->nameLokasi],
            ['DIREKTORAT KEPOLISIAN PERAIRAN DAN UDARA'],
            ['LAPORAN HASIL GIAT PROGRAM UNGGULAN POLISI RW DITPOLAIRUD '.$this->namePolda],
            [$this->nameTgl],
            [
                "NO",
                "POLDA",
                "JUMLAH POLISI RW",
                "KEGIATAN",
                "",
                "",
                "",
                "KET"
            ],
            [
                "",
                "",
                "",
                "SAMBANG",
                "PEMECAHAN MASALAH SOSIAL",
                "LAPORAN INFORMASI",
                "PENANGANAN PERKARA RINGAN",
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
            'G' => 50,
            'H' => 30
        ];
    }

    /**
     * @throws Exception
     */
    public function styles(Worksheet $sheet): void
    {
        $getTotal = $this->query()->count();
        $count = ($getTotal + 8);
        $total = 'H' . $count; ;
//        Header
//        IMAGE
        $sheet->getStyle('A1')->getActiveSheet()->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getRowDimension('1')->setRowHeight(90);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getAlignment()->setVertical('center');

        $sheet->getStyle('A2')->getActiveSheet()->mergeCells('A2:E2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getRowDimension('2')->setRowHeight(20);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2')->getAlignment()->setVertical('center');

        $sheet->getStyle('A3')->getActiveSheet()->mergeCells('A3:E3');
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getRowDimension('3')->setRowHeight(20);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3')->getAlignment()->setVertical('center');

        $sheet->getStyle('A4')->getActiveSheet()->mergeCells('A4:E4');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle('A4')->getFont()->setUnderline(true);
        $sheet->getRowDimension('4')->setRowHeight(30);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A4')->getAlignment()->setVertical('top');

        $sheet->getStyle('A5')->getActiveSheet()->mergeCells('A5:H5');
        $sheet->getStyle('A5')->getFont()->setBold(true);
        $sheet->getStyle('A5')->getFont()->setUnderline(true);
        $sheet->getRowDimension('5')->setRowHeight(25);
        $sheet->getStyle('A5')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A5')->getAlignment()->setVertical('top');

        $sheet->getStyle('A6')->getActiveSheet()->mergeCells('A6:E6');
        $sheet->getRowDimension('6')->setRowHeight(20);
        $sheet->getStyle('A6')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('A6')->getAlignment()->setVertical('top');
        $sheet->getStyle('A6')->getAlignment()->setIndent(1);
//        End Header

//        Header Table
        $sheet->getStyle('D7')->getActiveSheet()->mergeCells('E7:G7');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('A7:A8');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('B7:B8');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('C7:C8');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('H7:H8');
        $sheet->getStyle('A7:H8')->getFont()->setBold(true);
        $sheet->getStyle('A7:H8')->getFont()->setSize($this->format === 'pdf' ? 10 : 12);
        $sheet->getStyle('A7:H8')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A7:H8')->getAlignment()->setVertical('center');
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
