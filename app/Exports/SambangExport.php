<?php

namespace App\Exports;

use App\Models\report_sambang;
use App\Models\report_sapu;
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

class SambangExport implements FromQuery, WithHeadings, WithStyles, WithMapping, WithColumnWidths, WithDrawings
{
    use Exportable;

    private string $nameTgl;
    private string $poldaId;
    private string $namePolda;
    private string $nameLokasi;
    private string $tglDari;
    private string $tglSampai;
    private string $tipe;
    private string $format;

    public function __construct($nameTgl, $poldaId, $namePolda, $nameLokasi, $tglDari, $tglSampai, $tipe, $format)
    {
        $this->nameTgl = $nameTgl;
        $this->poldaId = $poldaId;
        $this->namePolda = $namePolda;
        $this->nameLokasi = $nameLokasi;
        $this->tglDari = $tglDari;
        $this->tglSampai = $tglSampai;
        $this->tipe = $tipe;
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
            $drawing->setOffsetX(0);
            $drawing->setCoordinates('E1');
        }

        return $drawing;
    }
    public function query(): Relation|Builder|\Illuminate\Database\Query\Builder
    {
        return report_sambang::query()
            ->leftJoin('poldas', 'poldas.id', 'report_sambangs.polda_id')
            ->select('poldas.name as polda_name',
                'report_sambangs.personil_jml',
                'report_sambangs.personil_sat',
                'report_sambangs.lokasi',
                'report_sambangs.jarak',
                'report_sambangs.uraian',
                'report_sambangs.jml_peserta',
                'report_sambangs.keterangan',
                'report_sambangs.tipe',
            )
            ->selectRaw('ROW_NUMBER() OVER(ORDER BY name) AS num_row')
            ->where('report_sambangs.polda_id', '=', $this->poldaId)
            ->where('report_sambangs.tipe', $this->tipe)
            ->where('report_sambangs.tanggal', '>=', $this->tglDari)
            ->where('report_sambangs.tanggal', '<=', $this->tglSampai);
    }
    public function sortPersonil($arr1, $arr2): string
    {
        $result = '';
        for ($i = 0; $i < count($arr1); $i++) {
            $result .= strtoupper($arr1[$i]) . " " . strtoupper($arr2[$i]) . "\n";
        }
        return $result;
    }
    public function formatDate($date, $time)
    {
        return $date . ' ' . $time. ' WIB';
    }
    public function map($report_sambang): array
    {
        $personil = $this->sortPersonil(
            json_decode($report_sambang->personil_jml),
            json_decode($report_sambang->personil_sat)
        );
        $tglwaktu = $this->formatDate($report_sambang->tanggal, $report_sambang->waktu);
        $jarak = $report_sambang->jarak . ' NM';
        return $report_sambang->num_row == 1 ? [
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [
                $report_sambang->num_row,
                $report_sambang->polda_name,
                $report_sambang->lokasi,
                $tglwaktu,
                $personil,
                $jarak,
                $report_sambang->tipe == '1' ? '√' : '',
                $report_sambang->tipe == '2' ? '√' : '',
                $report_sambang->tipe == '3' ? '√' : '',
                $report_sambang->uraian,
                $report_sambang->jml_peserta,
                $report_sambang->keterangan
            ]
        ] : [
            $report_sambang->num_row,
            $report_sambang->polda_name,
            $report_sambang->lokasi,
            $tglwaktu,
            $personil,
            $jarak,
            $report_sambang->tipe == '1' ? '√' : '',
            $report_sambang->tipe == '2' ? '√' : '',
            $report_sambang->tipe == '3' ? '√' : '',
            $report_sambang->uraian,
            $report_sambang->jml_peserta,
            $report_sambang->keterangan
        ];
    }
    public function headings(): array
    {
        return [
            [''],
            ['KEPOLISIAN NEGARA REPUBLIK INDONESIA'],
            [$this->nameLokasi],
            ['DIREKTORAT KEPOLISIAN PERAIRAN DAN UDARA'],
            ['LAPORAN HASIL GIAT PROGRAM UNGGULAN SAMBANG NUSA PRESISI DITPOLAIRUD '.$this->namePolda],
            [$this->nameTgl],
            [
                "NO",
                "SATKER",
                "LOKASI",
                "WAKTU",
                "JUMLAH PERS",
                "ROUTE / JARAK",
                "SASARAN SAMBANG NUSA",
                "",
                "",
                "URAIAN KEGIATAN",
                "JUMLAH PESERTA",
                "KETERANGAN / DOKUMENTASI"
            ],
            [
                "",
                "",
                "",
                "",
                "",
                "",
                "BINAAN",
                "SENTUHAN",
                "PANTAUAN",
                "",
                "",
                ""
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
            'L' => 20,
        ];
    }

    /**
     * @throws Exception
     */
    public function styles(Worksheet $sheet): void
    {
        $getTotal = $this->query()->count();
        $count = ($getTotal + 8);
        $total = 'L' . $count;
//        Header
//        IMAGE
        $sheet->getStyle('A1')->getActiveSheet()->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getRowDimension('1')->setRowHeight(90);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getAlignment()->setVertical('center');

        $sheet->getStyle('A2')->getActiveSheet()->mergeCells('A2:G2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getRowDimension('2')->setRowHeight(20);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2')->getAlignment()->setVertical('center');

        $sheet->getStyle('A3')->getActiveSheet()->mergeCells('A3:G3');
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getRowDimension('3')->setRowHeight(20);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3')->getAlignment()->setVertical('center');

        $sheet->getStyle('A4')->getActiveSheet()->mergeCells('A4:G4');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle('A4')->getFont()->setUnderline(true);
        $sheet->getRowDimension('4')->setRowHeight(30);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A4')->getAlignment()->setVertical('top');

        $sheet->getStyle('A5')->getActiveSheet()->mergeCells('A5:L5');
        $sheet->getStyle('A5')->getFont()->setBold(true);
        $sheet->getStyle('A5')->getFont()->setUnderline(true);
        $sheet->getRowDimension('5')->setRowHeight(25);
        $sheet->getStyle('A5')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A5')->getAlignment()->setVertical('top');

        $sheet->getStyle('A6')->getActiveSheet()->mergeCells('A6:G6');
        $sheet->getRowDimension('6')->setRowHeight(20);
        $sheet->getStyle('A6')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('A6')->getAlignment()->setVertical('top');
        $sheet->getStyle('A6')->getAlignment()->setIndent(1);
//        End Header

//        Header Table
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('A7:A8');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('B7:B8');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('C7:C8');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('D7:D8');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('E7:E8');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('F7:F8');
        $sheet->getStyle('G7')->getActiveSheet()->mergeCells('G7:I7');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('J7:J8');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('K7:K8');
        $sheet->getStyle('A8')->getActiveSheet()->mergeCells('L7:L8');
        $sheet->getStyle('A7:L8')->getFont()->setBold(true);
        $sheet->getStyle('A7:L8')->getFont()->setSize($this->format === 'pdf' ? 10 : 12);
        $sheet->getStyle('A7:L8')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A7:L8')->getAlignment()->setVertical('center');
        $sheet->getStyle('A7:L8')->getAlignment()->setWrapText(true);
//        End Header Table

//        Body
        $sheet->getStyle('A9:' . $total)->getAlignment()->setVertical('top');
        $sheet->getStyle('A9:A'. $count)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('G9:I'. $count)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A7:' . $total)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('B9:' . $total)->getAlignment()->setWrapText(true);
        $sheet->getStyle('B9:' . $total)->getAlignment()->setIndent(1);
        $sheet->getStyle('A7:' . $total)->getFont()->setSize($this->format === 'pdf' ? 10 : 11);
        $sheet->getStyle('A1')->getActiveSheet()->getPageSetup()->setPrintArea('A1:' . $total);
//        End Body
    }
}
